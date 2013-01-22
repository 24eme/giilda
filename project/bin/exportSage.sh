#!/bin/bash

. bin/config.inc

php symfony export:societe > $TMP/societes.csv
sort -t ';' -k 1,1 $TMP/societes.csv > $TMP/societes.sorted.csv
sort -t ';' -k 1,1 $TMP/InfosClientsSage.txt > $TMP/InfosClientsSage.sorted.txt
join -t ';' -1 1 -2 1 -a 1 $TMP/societes.sorted.csv $TMP/InfosClientsSage.sorted.txt > $TMP/societesWithSageData.csv
perl bin/convertExportSociete2SAGE.pl < $TMP/societesWithSageData.csv > $TMP/societes.sage

php symfony export:facture > $TMP/factures.csv
perl bin/convertExportFacture2SAGE.pl < $TMP/factures.csv > $TMP/factures.sage

echo  "#FLG 001";
echo "#VER 14";
echo "#DEV EUR";
cat $TMP/societes.sage
cat $TMP/factures.sage