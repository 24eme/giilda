#!/bin/bash

. bin/config.inc

#SAMBA_SAGEFILE="vide.csv"
#touch $TMP/$SAMBA_SAGEFILE
#php symfony export:societe $SYMFONYTASKOPTIONS > $TMP/societes.csv
#sort -t ';' -k 1,1 $TMP/societes.csv > $TMP/societes.sorted.csv
#sort -t ';' -k 1,1 $TMP/$SAMBA_SAGEFILE | iconv -f ISO88591 -t UTF8 | sed 's/\([^;a-z0-9éèçàïëê]*\)$/;\1/i' > $TMP/InfosClientsSage.sorted.txt
#join -t ';' -1 1 -2 1 -a 1 $TMP/societes.sorted.csv $TMP/InfosClientsSage.sorted.txt > $TMP/societesWithSageData.csv
#cat $TMP/societesWithSageData.csv | perl bin/convertExportSociete2SAGEv9.pl | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' > $TMP/societes.sage

php symfony export:facture $SYMFONYTASKOPTIONS --interpro=INTERPRO-UIVC --horstaxe=true > $TMP/factures.csv
cat $TMP/factures.csv | bash bin/convertExportFacture2EBP.sh | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' > $TMP/factures_ebp.csv

perl bin/convertExportFacture2SyntheseCsv.pl < $TMP/factures.csv > $TMP/factures_synthese.csv

bash bin/exportPostSageBL.sh $TMP/factures.csv

sed -i 's/\./,/g' $TMP/factures.csv

#echo "$TMP/societes.sage|societes.sage|Export SAGE des sociétés"
#echo "$TMP/societes.sorted.csv|societes.csv|Export CSV des sociétés"
echo "$TMP/factures_ebp.csv|factures_ebp.csv|Export EBP des factures"
echo "$TMP/factures.csv|factures.csv|Export CSV des factures"
echo "$TMP/factures_synthese.csv|factures_synthese.csv|Synthèse des factures"
