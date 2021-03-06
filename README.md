# TomTomSports
Extract data from the TomTom Sports Cloud

Since a while I have this TomTom sports & fitness watch and now I noticed there is an API available to extract the data: 
https://developer.tomtom.com

So let's see what we get when we try to access this ....
I made a small application using a mix of PHP and Python that is capable of extracting certain information from the TomTom cloud and copy that to a MySQL database. At this moment only the "tracking" table is copied over.

To get this up and running you need
* a server that is accessible over the internet as the TomTom will call an URL of your application to authorise it.
* an HTTP server
* capable of PHP
   * make sure that OpenSSL is enabled
   * allow_url_fopen is on
   * the user that runs the web server needs update rights on the config.ini file
* python, with these packages: 
   * requests
   * json
   * ast
   * pandas
   * pymysql
   * MySQLdb
   * sqlparse
* MySQL server

First step is to register the application at TomTom's web site, start here: https://developer.tomtom.com/ create a userid and create an application. 
<BR>This application has all its parameters in a config.ini file, copy the config_template.ini file to config.ini and edit it to reflect your parameters:

client_name=*your application name as registered at TomTom*
<BR>client_id=*the client ID as given to you by TomTom*
<BR>client_secret=*the client secret as given to you by TomTom*
<BR>api_key=*the API key as given to you by TomTom*  
<BR>redirect_uri=*the redirect URL as registered at TomTom-should point to the html/callback.php file on your server*
<BR>servername=*your MySQL server*
<BR>dbname=*The MySQL Database name*
<BR>username=*username of MySQL*
<BR>password=*password to access MySQL*
 
the TCP port of ySQL is currently hardcoded to 3307 

As the application communicates with the API of TomTom it will add additional parameters to the config.ini file. As stated above, the user running your webserver needs to have update rights on this config.ini file.

Once you have done all that, you can open the https://url_to_your_server/html/index.php page and continue from there. 
As soon as you have received an authorisation code, you can then manually start the getdata.py script to read in the data and copy it to the MySQL database.

I might bundle all of this in a Docker container as well.
