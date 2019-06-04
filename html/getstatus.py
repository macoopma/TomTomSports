import requests
import mysportmodule


# read in configuration 
config = {}
config = mysportmodule.read_config(config)

url = "https://api.tomtom.com/mysports/status"

headers = {
     "Api-Key" : config["api_key"]
 }

myResponse = requests.get(url, headers=headers)
print (myResponse.status_code)
print (myResponse.content)
