curl -s $1 | grep '"id":' | sed 's/.*"id":"//' | sed 's/".*//' | while read OBJ; do
	OBJREV=$(curl -s http://localhost:5984/vinsdeloire/$OBJ | sed 's/{"_id":"//' | sed 's/","_rev":"/?rev=/' | sed 's/".*//')
	curl -s -X DELETE http://localhost:5984/vinsdeloire/$OBJREV
done
