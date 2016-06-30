#!/bin/bash

. bin/config.inc

php symfony export:societe --application=declaration --env=$SYMFONYENV > $TMP/societes.csv

sort -t ';' -k 1,1 $TMP/societes.csv > $TMP/societes.sorted.csv
sort -t ';' -k 1,1 $TMP/$SAMBA_SAGEFILE | iconv -f ISO88591 -t UTF8 | sed 's/\([^;a-z0-9éèçàïëê]*\)$/;\1/i' > $TMP/InfosClientsSage.sorted.txt
join -t ';' -1 1 -2 1 -a 1 $TMP/societes.sorted.csv $TMP/InfosClientsSage.sorted.txt > $TMP/societesWithSageData.csv
cat $TMP/societesWithSageData.csv | perl bin/convertExportSociete2SAGE.pl > $TMP/societes.sage

php symfony export:csv-configuration --application=declaration --env=$SYMFONYENV > $TMP/produits.csv
php symfony export:facture --application=declaration --env=$SYMFONYENV | perl bin/preconvertExportFactureChapeau.pl $TMP/produits.csv > $TMP/factures.csv
cat $TMP/factures.csv | perl bin/convertExportFacture2SAGE.pl $TMP/produits.csv | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' > $TMP/factures.txt

echo -n > $TMP/$VINSIEXPORT
#echo  "#FLG 001" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
echo "#VER 14" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
echo "#DEV EUR" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
cat $TMP/societes.sage | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
cat $TMP/factures.sage | iconv -f UTF8 -t IBM437//TRANSLIT | sed 's/$/\r/' >> $TMP/$VINSIEXPORT
echo "#FIN" | sed 's/$/\r/' >> $TMP/$VINSIEXPORT


if test "$SAMBA_IP" && test "$SAMBA_SHARE" && test "$SAMBA_AUTH" && test "$SAMBA_SAGESUBDIR"; then
    cd $TMP
    smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; put $VINSIEXPORT"
    recode UTF8..ISO88591 societesWithSageData.csv
    smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; put societesWithSageData.csv societes.csv"
    recode UTF8..ISO88591 factures.csv
    smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; put factures.csv"
    test "$SAMBA_SAGEVERIFY" && smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; rm $SAMBA_SAGEFILE"
    cd -
    echo -n $(date '+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
    echo "$VINSIEXPORT a été mis à disposition avec succès" >> $TMP/$SAGE_EMAILFILE
else
    cat $TMP/$VINSIEXPORT
fi
