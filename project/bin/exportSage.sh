#!/bin/bash

. bin/config.inc

php symfony export:societe > $TMP/societes.csv
sort -t ';' -k 1,1 $TMP/societes.csv > $TMP/societes.sorted.csv
sort -t ';' -k 1,1 $TMP/InfosClientsSage.txt > $TMP/InfosClientsSage.sorted.txt
join -t ';' -1 1 -2 1 -a 1 $TMP/societes.sorted.csv $TMP/InfosClientsSage.sorted.txt > $TMP/societesWithSageData.csv
cat $TMP/societesWithSageData.csv | perl bin/convertExportSociete2SAGE.pl > $TMP/societes.sage

php symfony export:facture > $TMP/factures.csv
cat $TMP/factures.csv | perl bin/convertExportFacture2SAGE.pl > $TMP/factures.sage

echo  "#FLG 001" | sed 's/$/\r/'
echo "#VER 14" | sed 's/$/\r/'
echo "#DEV EUR" | sed 's/$/\r/'
cat $TMP/societes.sage | iconv -f UTF8 -t ISO8859-1 | sed 's/$/\r/'
cat $TMP/factures.sage | iconv -f UTF8 -t ISO8859-1 | sed 's/$/\r/'

