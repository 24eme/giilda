#!/bin/bash

. bin/config.inc

php symfony export:facture $SYMFONYTASKOPTIONS > $TMP/factures.csv

cat $TMP/factures.csv | bash bin/convertExportFacture2CIEL.sh | sed "s/$/\r/" | iconv -f utf-8 -t iso-8859-1 > $TMP/factures.txt

bash bin/exportPostSage.sh $TMP/factures.csv > /dev/null

echo "$TMP/factures.txt|factures.txt|Export CIEL des factures"
echo "$TMP/factures.csv|factures.csv|Export CSV des factures"
