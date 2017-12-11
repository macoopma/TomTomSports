import requests
import base64
import json
import ast

# read in configuration 
def read_config(config):
    with open("config.ini") as myfile:
        for line in myfile:
            name, var = line.partition("=")[::2]
            config[name.strip()] = var.strip()
    return config        

def writeconfig(config):
    fp = open('config.ini','w');
    try:
        # do stuff with f
        for k,v in config.items():
            # fp.write('%s=%s\n', k, v);
            fp.write('{}={}\n'.format(k, v))
            #print k 
            #print 'hallo'
    finally:
        fp.close()

def refreshtoken(config):
#    readconfig()
    print ("Starting refreshtoken : ")
    print (config)
    print ("++++++++++++++++++++++++++++++++++")
    
    url = 'https://api.tomtom.com/mysports/oauth2/token'

    #enc = base64.b64encode(bytes( config['client_id'] + ':' + config['client_secret'] , 'utf-8'))

    enc = base64.b64encode(config['client_id'] + ':' + config['client_secret'] )
    #print (enc)


    headers = {
        "Authorization" : "Basic " + enc ,
        "Api-Key" : config["api_key"]
     }

    #print (headers)


    body = {'grant_type' : 'refresh_token',
            'refresh_token': config['refresh_token']
		    }
    #print para 

    myResponse = requests.post(url, headers=headers, data=body)
    # print (myResponse.status_code)
    # print (myResponse.content)

    mystr = myResponse.content ;
    #print (mystr)

    val = ast.literal_eval(mystr)

    #print val
    val1 = json.loads(json.dumps(val))
    #print (val1) 

    config ["access_token"]  = val1["access_token"]
    config ["refresh_token"] = val1["refresh_token"]

    #print (config ["access_token"])

    #now just need to write back the config and all is done 
    writeconfig(config)
    return config

 