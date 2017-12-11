# TomTomSports
Extract data from the TomTom Sports Cloud

Since a while I have this TomTom sports & fitness watch and now I noticed there is an API available to extract the data: 
https://developer.tomtom.com

So let's see what we get when we try to access this ....

To get this up and running you need
* a server that is accessible over the internet as the TomTom will call an URL of your application to authorise it.
* an HTTP server
* capable of PHP
* python, with these packages: 
** requests
** json
** ast
** pandas
** pymysql
** MySQLdb
