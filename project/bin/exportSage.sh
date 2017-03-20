#!/bin/bash

. bin/config.inc

php symfony export:societe $SYMFONYTASKOPTIONS > $TMP/societes.csv

sort -t ';' -k 1,1 $TMP/societes.csv > $TMP/societes.sorted.csv
touch $TMP/$SAMBA_SAGEFILE
sort -t ';' -k 1,1 $TMP/$SAMBA_SAGEFILE | iconv -f ISO88591 -t UTF8 | sed 's/\([^;a-z0-9éèçàïëê]*\)$/;\1/i' > $TMP/InfosClientsSage.sorted.txt
join -t ';' -1 1 -2 1 -a 1 $TMP/societes.sorted.csv $TMP/InfosClientsSage.sorted.txt > $TMP/societesWithSageData.csv
cat $TMP/societesWithSageData.csv | perl bin/convertExportSociete2SAGE.pl | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/'  > $TMP/societes.txt

php symfony export:csv-configuration $SYMFONYTASKOPTIONS > $TMP/produits.csv
php symfony export:facture $SYMFONYTASKOPTIONS | perl bin/preconvertExportFactureChapeau.pl $TMP/produits.csv data/export/ivso_comptes2analytiques.csv > $TMP/factures.csv
cat $TMP/factures.csv | perl bin/convertExportFacture2SAGE.pl | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' > $TMP/factures.txt

echo -n > $TMP/$VINSIEXPORT
echo  "#FLG 001" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
echo "#VER 14" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
cat $TMP/societes.txt >> $TMP/$VINSIEXPORT
cat $TMP/factures.txt >> $TMP/$VINSIEXPORT
echo "#FIN" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT

echo -n > $TMP/factures.sage
echo  "#FLG 001" | sed 's/$/\r/' >> $TMP/factures.sage
echo "#VER 18" | sed 's/$/\r/' >> $TMP/factures.sage
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/factures.sage
cat $TMP/factures.txt >> $TMP/factures.sage
echo "#FIN" | sed 's/$/\r/' >> $TMP/factures.sage


echo -n > $TMP/societes.sage
echo  "#FLG 001" | sed 's/$/\r/' >> $TMP/societes.sage
echo "#VER 14" | sed 's/$/\r/' >> $TMP/societes.sage
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/societes.sage
cat $TMP/societes.txt >> $TMP/societes.sage
echo "#FIN" | sed 's/$/\r/' >> $TMP/societes.sage

bash bin/exportPostSage.sh $TMP/factures.csv

echo "$TMP/societes.sage|societes.sage|Export SAGE des sociétés"
echo "$TMP/societes.sorted.csv|societes.csv|Export CSV des sociétés"
echo "$TMP/factures.sage|factures.sage|Export SAGE des factures"
echo "$TMP/factures.csv|factures.csv|Export CSV des factures"
