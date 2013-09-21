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


