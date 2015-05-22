#!/bin/bash

. bin/config.inc

curl -s "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_design/compte/_view/all" | cut -d "," -f 1 | sed 's/{"id":"//' | sed 's/"//' | grep "^COMPTE" > $TMP/comptes_ldap_ids

while read ligne  
do
    echo $ligne
    php symfony compte:ldap-update $ligne
done < $TMP/comptes_ldap_ids

