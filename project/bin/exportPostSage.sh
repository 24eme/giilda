#!/bin/bash

. bin/config.inc

function sendEmail {
echo "Voici le compte rendu de l'import SAGE qui vient de s'effectuer :" >  $TMP/$SAGE_EMAILFILE.header
echo >> $TMP/$SAGE_EMAILFILE.header
echo "===========================================" >> $TMP/$SAGE_EMAILFILE.header

echo "===========================================" > $TMP/$SAGE_EMAILFILE.footer
echo "--" >>  $TMP/$SAGE_EMAILFILE.footer
echo "envoyé automatiquement depuis $0" >>  $TMP/$SAGE_EMAILFILE.footer


TITRE="Compte rendu"
if grep ERREUR $TMP/$SAGE_EMAILFILE > /dev/null ; then
TITRE="ERREUR"
fi
for email in $SAGE_EMAILS; do
cat $TMP/$SAGE_EMAILFILE.header $TMP/$SAGE_EMAILFILE $TMP/$SAGE_EMAILFILE.footer | iconv -f UTF8 -t ISO88591 | mail -s "[Import SAGE] $TITRE" $email
done
rm $TMP/$SAGE_EMAILFILE.header $TMP/$SAGE_EMAILFILE $TMP/$SAGE_EMAILFILE.footer
}

if ! test "$SAMBA_IP" || ! test "$SAMBA_SHARE" || ! test "$SAMBA_AUTH" || ! test "$SAMBA_SAGESUBDIR" || ! test "$SAMBA_SAGEFILE"; then
    echo "Pas d'info sur les contacts avec le serveur SAGE"
    exit 3
fi

cd $TMP
if smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; get societes.csv" | grep NT_STATUS_OBJECT_NAME_NOT_FOUND ; then
    echo "societes.csv not found" 1>&2
    echo -n $(date '+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
    echo "ERREUR le fichier societes.csv n'a pas été trouvé sur le répertoire de partage" >> $TMP/$SAGE_EMAILFILE
    sendEmail
    exit 4
fi
if smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; get factures.csv" | grep NT_STATUS_OBJECT_NAME_NOT_FOUND ; then
    echo "factures.csv not found" 1>&2
    echo -n $(date '+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
    echo "ERREUR le fichier factures.csv n'a pas été trouvé sur le répertoire de partage" >> $TMP/$SAGE_EMAILFILE
    sendEmail
    exit 5
fi
if test "$SAMBA_SAGEVERIFY" && smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; ls  $VINSIEXPORT" | grep -v NT_STATUS_NO_SUCH_FILE ; then
    echo "$VINSIEXPORT should not be present" 1>&2
    echo -n $(date'+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
    echo "ERREUR IMPORT SAGE (le fichier $VINSIEXPORT ne devrait pas être present)" >> $TMP/$SAGE_EMAILFILE
    sendEmail
    exit 3
fi


echo -n $(date '+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
echo $(cut -d ';' -f 14 /tmp/prod/factures.csv | sort | uniq | wc -l)" facture(s) importée(s) sans erreur " >> $TMP/$SAGE_EMAILFILE
sendEmail

cd -
mkdir -p $PDFDIR/csv
cp $TMP/factures.csv $PDFDIR/csv/$(date '+%Y%m%d')_factures.csv
bash bin/exportFacturePDF.sh

cat $TMP/factures.csv | awk -F ';' '{print $14}' | sort | uniq | while read FACTUREID; do
    php symfony facture:setexported $FACTUREID;
done

smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGESUBDIR ; rm factures.csv ; rm societes.csv"
