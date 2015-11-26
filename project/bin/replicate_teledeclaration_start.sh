#!/bin/bash

. bin/config.inc

DOC_REV=$(curl -s "http://$COUCHHOST:$COUCHPORT/_replicator/REPLICATION_TELEDECLARATION" | grep -Eo "_rev\":\"[0-9a-Z-]+\"" | sed 's/_rev":"//' | sed 's/"//')

if test "$DOC_REV" ; then
  echo "THE DOC ALREADY EXIST"
  exit
fi

ping -c 2 $(echo $REPLICATE_TELEDECLARATION_URL | sed 's/http...//' | sed 's/:.*//')

echo "{\"_id\": \"REPLICATION_TELEDECLARATION\", \"source\":\"$COUCHBASE\",\"target\":\"$REPLICATE_TELEDECLARATION_URL\",\"continuous\": true}" > /tmp/params_replication_teleclaration.json

curl -H "Content-Type: application/json" -X POST -d '@/tmp/params_replication_teleclaration.json' "http://$COUCHHOST:$COUCHPORT/_replicator"
