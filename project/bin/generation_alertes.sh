#!/bin/bash

DATE=$(date +%Y%m%d%H%M%S)

mkdir -p /tmp/generation_alertes > /dev/null

TEMPFILE=/tmp/generation_alertes/counterTask

echo 0 > $TEMPFILE

ps ux | awk '/generate/ && !/awk/ {print $2}' | while read line
do
       echo $DATE;
       echo "GENERATION EN COURS D'ACTIVITE => "$DATE >> "/tmp/generation_alertes/activite_generation_alertes.log" 2>&1
       echo 1 > $TEMPFILE
       break;
done; 

PROCESSEXIST=$[$(cat $TEMPFILE)]

if [ $PROCESSEXIST -eq 1 ]
then
      exit 1;
fi

unlink $TEMPFILE
cd ..
bash bin/alerte_creation_update 0 > "/tmp/generation_alertes/generation_alertes_"$DATE".log" &