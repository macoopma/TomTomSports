import requests
from requests.auth import HTTPDigestAuth
import json
import ast
import datetime
import pandas as pd

from pandas.io import sql
from sqlalchemy import create_engine

import pymysql
pymysql.install_as_MySQLdb()
import MySQLdb

import mysportmodule


'''
still to do:
  if it is the first time connecting,
        create the table in MySQL => DONE - we read the SQL from an external file
        have to read the whole history => DONE - we read a max of 5000 days
  when the access_token is not working refresh it using the refresh token, and write back to the config => Done
  a lot of error checking 

'''

# read in configuration 

config = {}
config = mysportmodule.read_config(config)

# check how many days we need to go back n take the highest date and delete that record
# https://stackoverflow.com/questions/372885/how-do-i-connect-to-a-mysql-database-in-python

db = MySQLdb.connect(host=config ['servername'] ,    # your host, usually localhost
                     user=config ['username'],         # your username
                     passwd=config ['password'],  # your password
                     db=config ['dbname'])        # name of the data base

# you must create a Cursor object. It will let you execute all the queries you need
cur = db.cursor()

# let's check if the TRACKING table exists, otherwise we will create it:
stmt = "SHOW TABLES LIKE 'tracking'"
cur.execute(stmt)
result = cur.fetchone()
if not result:
    # there are no tables named "tableName"
    with open ("createtable.sql", "r") as myfile:
        sql=myfile.read()
        cur.execute (sql)
        
# We have to delete the record with the highest date, as when we last synchronised probably the day was not yet complete. 
# select the highest date
cur.execute("SELECT max(date) FROM tracking")
for row in cur.fetchall():
    maxdate=row[0]

if (maxdate == None) : 
    # this script is probably running against a new database, let's read in 5000 days
    diff= 5000 
    maxdate= datetime.datetime.today() + datetime.timedelta (days=-diff)

else:  
    try:       
        cur.execute("delete from `tracking` WHERE `date`  = %s ", maxdate) 
        db.commit()
        d1=  datetime.date.today() -maxdate.date()
        diff=d1.days + 1
    except:
        print ("!!!!! !!!! there was an error in the commit ")    
db.close()

# we now have calculated the number of days since the last synchronise and today.
# Is the watch synchronised today that is the number of days we need to read from the TomTom API. 
# We need to filter later if we are not making a mistake    
# print ("we need %s days to read" , str(diff.days))

url = 'https://api.tomtom.com/mysports/1/tracking?days=' + str(diff)

#url = 'https://api.tomtom.com/mysports/1/tracking?days=5'

headers = {
    "Authorization" : "Bearer " + config["access_token"] ,
    "Api-Key" : config["api_key"]
 }

myResponse = requests.get(url, headers=headers)
#print (myResponse.status_code)
#print (myResponse.content)


# check of the previous request went find, otherwise renew the access token using the refresh token
if (myResponse.status_code == 401):
    config = mysportmodule.refreshtoken (config)
    headers = {
    "Authorization" : "Bearer " + config["access_token"] ,
    "Api-Key" : config["api_key"]
     }
    myResponse = requests.get(url, headers=headers)

mystr = myResponse.content  
val = ast.literal_eval(mystr)
val1 = json.loads(json.dumps(val))


#print ("val1 is")
#print (val1)

# let's make a dictionary with all the items we want to read from the data
data = {"steps":"origin_device",
        "metabolic_energy":"origin_device",
        "active_time":"origin_device", 
        "distance":"origin_device",
        "sleep":"origin_device",
        "muscle":"summary",
        "fat":"summary",
        "weight":"summary",
        "hr_min":"summary",
        "hr_max":"summary",
        "hr_avg":"summary",
        "hr_rest":"summary"

        }

first_run = True
for data_item in data.keys():
    data_dict={}
    for serie in val1['series'][data_item]:
        for k,v in serie.items():
            if (k >= str(maxdate.date())) :
                this_data= v.get (data[data_item],0)
                data_dict[k] = this_data

    if first_run :
        first_run = False
        table_pd = pd.DataFrame (data_dict.items(), columns=['date', data_item]) 
    else:
        table_pd[data_item] = table_pd['date'].map(data_dict)

table_pd['date'] = pd.to_datetime (table_pd['date'])

table_pd=table_pd.sort_values ('date')

engine = create_engine("mysql+pymysql://{user}:{pw}@{servername}/{db}"
                       .format(user=config['username'],
                               pw=config["password"],
                               servername = config['servername'],
                               db=config["dbname"]))

table_pd.to_sql(con=engine, name='tracking', if_exists='append')

#end