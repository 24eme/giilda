#!/bin/bash

if ! test $(grep metabase bin/confi*inc | grep -v example | sed 's/.*=//'  | sed 's|/db/.*||'  | sort -u | wc -l) -eq 1; then
	exit 1;
fi

MB_DB_DBNAME=metabase
MB_DB_PORT=3306
MB_DB_USER=metabase

project_bin_config=$(grep -l metabase bin/confi*inc | grep -v example | head -n 1)
source $project_bin_config
metabase_path=$( grep metabase $project_bin_config | sed 's/.*=//'  | sed 's|/db/.*||' | head -n 1 )
source $metabase_path"/bin/config.inc"

if ! test  $COUCHDISTANTHOST ; then 
	exit 2;
fi

if test "$MB_DB_TYPE" == "mysql"; then 

echo "dumping mysql $MB_DB_DBNAME from $COUCHDISTANTHOST"
if ! mysqldump -h $COUCHDISTANTHOST --port="$MB_DB_PORT" -u $MB_DB_USER --password="$MB_DB_PASS"  $MB_DB_DBNAME > "/tmp/metabase_"$MB_DB_DBNAME".sql" ; then
	echo "ERROR: mysqldump for $MB_DB_DBNAME failed"
	exit 3;
fi

cat "/tmp/metabase_"$MB_DB_DBNAME".sql" | mysql --port="$MB_DB_PORT" -u $MB_DB_USER --password="$MB_DB_PASS"  $MB_DB_DBNAME

if test $? -ne 0; then
        echo "ERROR: mysql import for $MB_DB_DBNAME failed"
        exit 4;
fi

rm "/tmp/metabase_"$MB_DB_DBNAME".sql"

else

rsync --exclude='*sqlite*' -a $COUCHDISTANTHOST":"$metabase_path"/db/" $metabase_path"/db"

fi
