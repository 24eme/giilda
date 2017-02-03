#!/bin/bash

. bin/config.inc

php symfony export:facture --application=declaration --env=$SYMFONYENV > $TMP/factures.csv

cat $TMP/factures.csv | bash bin/convertExportFacture2CIEL.sh | iconv -f utf-8 -t iso-8859-1 | sed 's/$/\r/' > $TMP/factures.txt

bash bin/exportPostSage.sh $TMP/factures.csv > /dev/null

echo "$TMP/factures.txt|factures.txt|Export CIEL des factures"
echo "$TMP/factures.csv|factures.csv|Export CSV des factures"
