#!/bin/bash

. bin/config.inc

php symfony export:societe --application=declaration --env=$SYMFONYENV > $TMP/societes.csv

sort -t ';' -k 1,1 $TMP/societes.csv > $TMP/societes.sorted.csv
sort -t ';' -k 1,1 $TMP/$SAMBA_SAGEFILE | iconv -f ISO88591 -t UTF8 | sed 's/\([^;a-z0-9éèçàïëê]*\)$/;\1/i' > $TMP/InfosClientsSage.sorted.txt
join -t ';' -1 1 -2 1 -a 1 $TMP/societes.sorted.csv $TMP/InfosClientsSage.sorted.txt > $TMP/societesWithSageData.csv
cat $TMP/societesWithSageData.csv | perl bin/convertExportSociete2SAGE.pl > $TMP/societes.sage

php symfony export:csv-configuration --application=declaration --env=$SYMFONYENV > $TMP/produits.csv
php symfony export:facture --application=declaration --env=$SYMFONYENV | perl bin/preconvertExportFactureChapeau.pl $TMP/produits.csv > $TMP/factures.csv
cat $TMP/factures.csv | perl bin/convertExportFacture2SAGE.pl $TMP/produits.csv > $TMP/factures.sage

echo -n > $TMP/$VINSIEXPORT
#echo  "#FLG 001" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
echo "#VER 14" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
cat $TMP/societes.sage | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
cat $TMP/factures.sage | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
echo "#FIN" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT

echo "#FLG 001" | sed 's/$/\r/'  > $TMP/factures.withHeader.sage
echo "#VER 18"  | sed 's/$/\r/' >> $TMP/factures.withHeader.sage
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/factures.withHeader.sage
cat $TMP/factures.sage | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' >> $TMP/factures.withHeader.sage
echo "#FIN" | sed 's/$/\r/' >> $TMP/factures.withHeader.sage
mv $TMP/factures.withHeader.sage $TMP/factures.sage

echo "#FLG 001" | sed 's/$/\r/'  > $TMP/socites.withHeader.sage
echo "#VER 14"  | sed 's/$/\r/' >> $TMP/socites.withHeader.sage
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/socites.withHeader.sage
cat $TMP/societes.sage | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' >> $TMP/societes.withHeader.sage
echo "#FIN" | sed 's/$/\r/' >> $TMP/societes.withHeader.sage
mv $TMP/societes.withHeader.sage $TMP/societes.sage

echo "$TMP/societes.sage|societes.sage|Export SAGE des sociétés"
echo "$TMP/societes.sorted.csv|societes.csv|Export CSV des sociétés"
echo "$TMP/factures.sage|factures.sage|Export SAGE des factures"
echo "$TMP/factures.csv|factures.csv|Export CSV des factures"

