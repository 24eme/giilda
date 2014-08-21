#!/bin/bash

. bin/config.inc

DOC_FILTER_ID=replication_teledeclaration
DOC_FILTER_REV=$(curl -s "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_design/replication_teledeclaration" | grep -Eo "_rev\":\"[0-9a-Z-]+\"" | sed 's/_rev":"//' | sed 's/"//')

echo "{\"source\":\"$COUCHBASE\",\"target\":\"$REPLICATE_TELEDECLARATION_URL\",\"continuous\": true}" > /tmp/params_replication_teleclaration.json

curl -H "Content-Type: application/json" -X POST -d '@/tmp/params_replication_teleclaration.json' "http://$COUCHHOST:$COUCHPORT/_replicate"