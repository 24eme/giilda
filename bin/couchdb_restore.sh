#!/bin/bash

BACKUPFILE=$1
DESTINATION=$2
LVMDIR=/tmp/lvmtmp

if ! test "$1" || ! test "$2" ; then
    echo "Usage: $0 <BACKUPFILE> <DESTINATIONFILE>" > /dev/stderr
    exit 1
fi

if ! echo $BACKUPFILE | grep '.couch$' > /dev/null ; then
    echo "ERROR: $BACKUPFILE should be a .couch file" > /dev/stderr ;
    exit 2;
fi

if ! echo $DESTINATION | grep '.couch$' > /dev/null ; then
    echo "ERROR: $DESTINATION should be a .couch file" > /dev/stderr ;
    exit 2;
fi

sudo rsync -a  $BACKUPFILE  $DESTINATION 
sudo chown couchdb.couchdb $DESTINATION 
sudo chmod 644 $DESTINATION

