. bin/config.inc

curl -s "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_design/facture/_view/etablissement" | cut -d ":" -f 2 | sed 's/","key"//' | sed 's/"//' | grep "FACTURE" > /tmp/factures_ids

cat /tmp/factures_ids | while read line; do
	php symfony maintenance:facture-stockage-numero-interloire $line
done