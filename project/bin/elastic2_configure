#!/bin/bash
. bin/config.inc

if ! curl -s http://$ELASTHOST:$ELASTPORT/ > /dev/null 2>&1 ; then
    echo "Elastic search not running or not configured (see bin/config.inc) : http://$ELASTHOST:$ELASTPORT/ "
    exit 1
fi

echo '{
  "_id": "_design/app",
  "filters": {
    "type": "function(doc, req) { if(doc.type == req.query.type) { return true; } if(doc._id.replace(/-.*/, '"''"') == req.query.type.toUpperCase()) { return true; } return false;}"
  }
}
' > /tmp/filter.json

REV=$(curl -s http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_design/app | sed 's/.*_rev":"//' | sed 's/".*//')
if test "$REV" ; then
    curl -s -X DELETE "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_design/app?rev=$REV" > /dev/null
fi

curl -s -X PUT -d '@/tmp/filter.json' http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_design/app > /dev/null


if curl -s http://$ELASTHOST:$ELASTPORT/$ELASTBASE | grep -v "IndexMissingException" > /dev/null 2>&1 ; then
    curl -s -X DELETE http://$ELASTHOST:$ELASTPORT/$ELASTBASE > /dev/null
fi

curl -s -XPUT "http://$ELASTHOST:$ELASTPORT/$ELASTBASE" -d '@data/elk/elasticsearch.mapping' > /dev/null


cat data/elk/logstash.conf | sed "s/ELASTHOST/$ELASTHOST/g" | sed "s/ELASTPORT/$ELASTPORT/g" | sed "s/ELASTBASE/$ELASTBASE/g" | sed "s/COUCHHOST/$COUCHHOST/g" | sed "s/COUCHPORT/$COUCHPORT/g" | sed "s/COUCHBASE/$COUCHBASE/g" > "/tmp/"$ELASTBASE".conf"
echo write logstash configuration in /etc/logstash/conf.d
sudo mv "/tmp/"$ELASTBASE".conf" /etc/logstash/conf.d
