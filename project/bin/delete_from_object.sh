. bin/config.inc

	
OBJREV=$(curl -s $1 | sed 's/{"_id":"//' | sed 's/","_rev":"/?rev=/' | sed 's/".*//')
curl -s -X DELETE http://$COUCHHOST:$COUCHPORT/$COUCHBASE/$OBJREV
