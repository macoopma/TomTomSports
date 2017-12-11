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
* python, with these packages: 
   * requests
   * json
   * ast
   * pandas
   * pymysql
   * MySQLdb
* MySQL server

First step is to register the application at TomTom's web site, start here: https://developer.tomtom.com/ create a userid and create an application. 
<BR>This application has all its parameters in a config.ini file, copy the config_template.ini file to config.ini and edit it to reflect your parameters:

client_name=*your application name as registered at TomTom*
<BR>client_id=*the client ID as given to you by TomTom*
<BR>redirect_uri=*the redirect URL as registered at TomTom-should point to the html/callback.php file on your server*
<BR>servername=*your MySQL server*
<BR>dbname=*The MySQL Database name*
<BR>username=*username of MySQL*
<BR>password=*password to access MySQL*
  
As the application communicates with the API of TomTom it will add additional parameters to the config.ini file.

Once you have done all that, you can open the https://url_to_your_server/html/index.php page and continue from there.
