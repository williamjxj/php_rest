#!/bin/bash

# insert into restapi(username, stat_name, stat_value) values('user01', 'points', 100);
# $RANDOM: 0 - 32767 (signed 16-bit integer)

USER=rest
PASS='ldrly.com'
DB=rest
SQL='insert into restapi(username, stat_name, stat_value) values'
PL='./generate_data.pl'

if [ -x $PL ]; then
  SQL2=`$PL`
fi

mysql -u $USER --password=$PASS -D $DB -h localhost <<__EOF__
 TRUNCATE TABLE restapi;

 $SQL $SQL2;

__EOF__
