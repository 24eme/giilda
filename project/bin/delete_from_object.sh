. bin/config.inc

OBJREV=$(curl -s $1 | sed 's/{"_id":"//' | sed 's/","_rev":"/?rev=/' | sed 's/".*//')
if test "$OBJREV" ; then
	curl -s -X DELETE http://$COUCHHOST:$COUCHPORT/$COUCHBASE/$OBJREV
else
	echo "Document non trouvé. Il faut mettre l'url du document"
fi
