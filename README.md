PHP + Slim Framework + MySQL + jQuery version for REST API (with support of Perl script and bash script).

See the demo from <a href="http://dixitruth.com/php_rest/">http://dixitruth.com/php_rest/</a>


API calling from browsers, curl all work, such as:

- curl -i -X GET http://dixitruth.com/php_rest/getStats/user01
 
- curl -i -X POST -H 'Content-Type: application/json' -d 
   '{"username": "Tester", "stat_name":"points", "stat_value":888}' 
   http://dixitruth.com/php_rest/sendStat

- curl -i -X GET http://dixitruth.com/php_rest/getLeaderboard/points

demo: http://dixitruth.com/php_rest/