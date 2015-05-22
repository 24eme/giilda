#!/bin/bash

. bin/config.inc

curl -s "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_design/compte/_view/all" | cut -d "," -f 1 | sed 's/{"id":"//' | sed 's/"//' | grep "^COMPTE" > $TMP/comptes_ids

while read ligne  
do
    echo $ligne
    php symfony compte:update $ligne
done < $TMP/comptes_ids

