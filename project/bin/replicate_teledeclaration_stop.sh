#!/bin/bash

. bin/config.inc

DOC_REV=$(curl -s "http://$COUCHHOST:$COUCHPORT/_replicator/REPLICATION_TELEDECLARATION" | grep -Eo "_rev\":\"[0-9a-Z-]+\"" | sed 's/_rev":"//' | sed 's/"//')

if ! test "$DOC_REV" ; then
  echo "THE DOC DOES NOT EXIST"
  exit
fi

curl -H "Content-Type: application/json" -X DELETE "http://$COUCHHOST:$COUCHPORT/_replicator/REPLICATION_TELEDECLARATION?rev=$DOC_REV"