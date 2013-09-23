slim framework


$ cd ~

$ git clone git@github.com:williamjxj/php_rest.git

$ cd php_rest



MySQL Database Initialize
==========================

1. Create Database and Table:
$ bin/init_db.sh

2. Insert mock data into Table 'restapi':
$ init_table.sh

There are 2 assist Files:
(1) stat_name.txt:  used for add/modify/delete stat_name
(2) generate_data.pl: a Perl script to generate (100X10) dynamic records.


3. Slim Framework
==================
http://docs.slimframework.com/

$ curl -s https://getcomposer.org/installer | php

Create a composer.json file in your project root:
{
  "require": {
	 "slim/slim": "2.*"
  }
}

Install via composer:

$ php composer.phar install


github:
=======
https://github.com/williamjxj/php_rest

$ git clone git@github.com:williamjxj/php_rest.git

for modifying purpose:
$ git remote add origin git@github.com:williamjxj/php_rest.git
$ git push -u origin master

Test
=====

curl -i -X GET http://dixitruth.com/php_rest/getStats/user01
curl -i -X GET http://dixitruth.com/php_rest/getStats/user88

curl -i -X GET http://dixitruth.com/php_rest/getLeaderboard/points
curl -i -X GET http://dixitruth.com/php_rest/getLeaderboard/kills

curl -i -X POST -H 'Content-Type: application/json' -d '{"username": "Tester", "stat_name":"points", "stat_value":888}' http://dixitruth.com/php_rest/sendStat
curl -i -X PUT -H 'Content-Type: application/json' -d '{"username": "user01", "stat_name": "points", "stat_value": 999}' http://dixitruth.com/php_rest/sendStat

curl -v -H "Accept: application/json" -H "Content-type: application/json"  http://dixitruth.com/php_rest/getStats/user09/json
curl -v -H "Accept: application/json" -H "Content-type: application/json"  http://dixitruth.com/php_rest/getLeaderboard/points/json
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X POST -d '{"username":"Tester1", "stat_name":"points", "stat_value":123}' http://dixitruth.com/php_rest/sendStat

