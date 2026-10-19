#!/bin/bash

. bin/config.inc

php symfony export:facture $SYMFONYTASKOPTIONS --interpro=INTERPRO-UIVC --horstaxe=true > $TMP/factures.csv
cat $TMP/factures.csv | bash bin/convertExportFacture2EBP.sh | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' > $TMP/factures_ebp.csv

perl bin/convertExportFacture2SyntheseCsv.pl < $TMP/factures.csv > $TMP/factures_synthese.csv

bash bin/exportPostSageBL.sh $TMP/factures.csv

sed -i 's/\./,/g' $TMP/factures.csv

echo "$TMP/factures_ebp.csv|factures_ebp.csv|Export EBP des factures"
echo "$TMP/factures.csv|factures.csv|Export CSV des factures"
echo "$TMP/factures_synthese.csv|factures_synthese.csv|Synthèse des factures"
