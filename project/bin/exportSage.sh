#!/bin/bash

. bin/config.inc

if test "$SAMBA_IP$SAMBA_SHARE$SAMBA_AUTH$SAMBA_SAGESUBDIF$SAMBSAGEFILE"; then
    cd $TMP
    smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBID ; get $SAMBA_SAGEFILE"
    cd -
fi

php symfony export:societe > $TMP/societes.csv
sort -t ';' -k 1,1 $TMP/societes.csv > $TMP/societes.sorted.csv
sort -t ';' -k 1,1 $TMP/$SAMBA_SAGEFILE > $TMP/InfosClientsSage.sorted.txt
join -t ';' -1 1 -2 1 -a 1 $TMP/societes.sorted.csv $TMP/InfosClientsSage.sorted.txt > $TMP/societesWithSageData.csv
cat $TMP/societesWithSageData.csv | perl bin/convertExportSociete2SAGE.pl > $TMP/societes.sage

php symfony export:facture > $TMP/factures.csv
cat $TMP/factures.csv | perl bin/convertExportFacture2SAGE.pl > $TMP/factures.sage

VINSIEXPORT=vinsi.export.sage.txt
echo  "#FLG 001" | sed 's/$/\r/' > $TMP/$VINSIEXPORT
echo "#VER 14" | sed 's/$/\r/' > $TMP/$VINSIEXPORT
echo "#DEV EUR" | sed 's/$/\r/' > $TMP/$VINSIEXPORT
cat $TMP/societes.sage | iconv -f UTF8 -t ISO8859-1 | sed 's/$/\r/' > $TMP/$VINSIEXPORT
cat $TMP/factures.sage | iconv -f UTF8 -t ISO8859-1 | sed 's/$/\r/' > $TMP/$VINSIEXPORT
echo "#FIN" | sed 's/$/\r/' > $TMP/$VINSIEXPORT


if test "$SAMBA_IP$SAMBA_SHARE$SAMBA_AUTH$SAMBA_SAGESUBDIF$SAMBSAGEFILE"; then
    cd $TMP
    smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBID ; put $VINSIEXPORT"
    cd -
else
    cat $VINSIEXPORT
fi
