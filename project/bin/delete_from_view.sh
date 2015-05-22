. bin/config.inc

curl -s $1 | grep '"id":' | sed 's/.*"id":"//' | sed 's/".*//' | while read OBJ; do
	OBJREV=$(curl -s http://$COUCHHOST:$COUCHPORT/$COUCHBASE/$OBJ | sed 's/{"_id":"//' | sed 's/","_rev":"/?rev=/' | sed 's/".*//')
	curl -s -X DELETE http://$COUCHHOST:$COUCHPORT/$COUCHBASE/$OBJREV
done
