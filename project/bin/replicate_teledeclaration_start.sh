#!/bin/bash

. bin/config.inc

echo "{\"source\":\"$COUCHBASE\",\"target\":\"$REPLICATE_TELEDECLARATION_URL\",\"continuous\": true}" > /tmp/params_replication_teleclaration.json

curl -H "Content-Type: application/json" -X POST -d '@/tmp/params_replication_teleclaration.json' "http://$COUCHHOST:$COUCHPORT/_replicate"