#!/bin/bash

. bin/config.inc

DOC_ID=$1
HASH_FROM=$2
HASH_TO=$3

HASH_FROM_WITH_TIRET=$(echo $HASH_FROM | sed 's|/|-|g')
HASH_TO_WITH_TIRET=$(echo $HASH_TO | sed 's|/|-|g')

php symfony document:replace-hash $DOC_ID --from="$HASH_FROM" --to="$HASH_TO" > /tmp/remplacement_hash_doc_$DOC_ID
php symfony document:replace-hash $DOC_ID --from="$HASH_FROM_WITH_TIRET" --to="$HASH_TO_WITH_TIRET" >> /tmp/remplacement_hash_doc_$DOC_ID
php symfony document:replace-value $DOC_ID --from="$HASH_FROM" --to="$HASH_TO" >> /tmp/remplacement_hash_doc_$DOC_ID

if [[ $(cat /tmp/remplacement_hash_doc_$DOC_ID | wc -l) -gt 0 ]]
then
	php symfony document:update $DOC_ID
fi;

cat /tmp/remplacement_hash_doc_$DOC_ID

rm /tmp/remplacement_hash_doc_$DOC_ID