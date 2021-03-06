#!/bin/bash
# William: create Database & Table for Rest API assignment.
#  UNIQUE KEY username (username) USING BTREE

mysql -u root --password="" -h localhost <<EOF

drop database if exists rest;

create database rest COLLATE = 'utf8_general_ci';

use rest;

grant all on rest.* to rest@localhost identified by 'ldrly.com';

CREATE TABLE IF NOT EXISTS restapi (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  username varchar(100) NOT NULL,
  stat_name varchar(100) NOT NULL,
  stat_value int(11) NOT NULL,
  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE NOW(),
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


ALTER TABLE  restapi ADD INDEX (  username );
ALTER TABLE  restapi ADD INDEX (  stat_name );
ALTER TABLE  restapi ADD UNIQUE ( username, stat_name);

EOF

if [ $? -eq 0 ]; then
  echo
  echo "Database 'REST', Table: 'restapi' is created Successfully!"
  echo " -- user/pass: rest/ldrly.com."
  echo "Use './init_table.sh' to initialize!"
  echo
fi
