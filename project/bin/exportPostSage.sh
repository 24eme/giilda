#!/bin/bash

. bin/config.inc

function sendEmail {
    #Export comptable avant l'envoi de mail afin de s'assurer qu'il soit dispo quoi qu'il arrive
    mkdir -p $PDFDIR"/export"
    annee=$(date '+%Y')
    cd $SYMFONYROOT
    php symfony export:facture-annee-comptable $annee"-01" $annee"-12" > $PDFDIR"/export/"$annee"_export_comptable.csv.tmp"
    mv $PDFDIR"/export/"$annee"_export_comptable.csv.tmp" $PDFDIR"/export/"$annee"_export_comptable.csv"

    mkdir -p $PDFDIR/csv
    cp $TMP/factures.csv $PDFDIR/csv/$(date '+%Y%m%d')_factures.csv
    cp $TMP/societes.csv $PDFDIR/csv/$(date '+%Y%m%d')_societes.csv
    cp $TMP/societes.csv $PDFDIR/csv/societes.last.csv

    if test $SAMBA_FACTURELOCALDIR; then
        sudo mount $SAMBA_FACTURELOCALDIR
        rsync -a $PDFDIR $SAMBA_FACTURELOCALDIR
        sudo umount $SAMBA_FACTURELOCALDIR
    fi

    #Envoi du mail
    echo "Voici le compte rendu de l'import SAGE qui vient de s'effectuer :" >  $TMP/$SAGE_EMAILFILE.header
    echo >> $TMP/$SAGE_EMAILFILE.header
    echo "===========================================" >> $TMP/$SAGE_EMAILFILE.header

    echo "===========================================" > $TMP/$SAGE_EMAILFILE.footer
    echo >>  $TMP/$SAGE_EMAILFILE.footer
    echo "--" >>  $TMP/$SAGE_EMAILFILE.footer
    echo "envoyé automatiquement depuis "$USER"@"$HOSTNAME":"$0 >>  $TMP/$SAGE_EMAILFILE.footer


    TITRE="Compte rendu"
    if grep ERREUR $TMP/$SAGE_EMAILFILE > /dev/null ; then
        TITRE="ERREUR"
    fi
    for email in $SAGE_EMAILS; do
            cat $TMP/$SAGE_EMAILFILE.header $TMP/$SAGE_EMAILFILE $TMP/$SAGE_EMAILFILE.footer | iconv -f UTF8 -t ISO88591 | mail -s "[Import SAGE] $TITRE" $email
        done
    cat $TMP/$SAGE_EMAILFILE.header $TMP/$SAGE_EMAILFILE $TMP/$SAGE_EMAILFILE.footer > log/mail/$(date +%Y%m%d)"_SAGE.txt"
    rm $TMP/$SAGE_EMAILFILE.header $TMP/$SAGE_EMAILFILE $TMP/$SAGE_EMAILFILE.footer
}

if ! test "$SAMBA_IP" || ! test "$SAMBA_SHARE" || ! test "$SAMBA_AUTH" || ! test "$SAMBA_SAGEEXP_SUBDIR" || ! test "$SAMBA_SAGEFILE"; then
    echo "ERREUR: Pas d'info sur les contacts avec le serveur SAGE (pb de configuration de VINSI)"
    exit 3
fi
SYMFONYROOT=$(pwd)
cd $TMP
if smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGEEXP_SUBDIR ; get societes.csv" | grep NT_STATUS_OBJECT_NAME_NOT_FOUND ; then
    echo "societes.csv not found" 1>&2
    echo -n $(date '+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
    echo "ERREUR le fichier societes.csv n'a pas été trouvé sur le répertoire de partage ($0)" >> $TMP/$SAGE_EMAILFILE
    echo "DIAGNOSTIQUE: le transfer des fichiers VINSI ne semble pas s'être bien passé lors de l'etape exportSage" >> $TMP/$SAGE_EMAILFILE
    sendEmail
    exit 4
fi
if smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGEEXP_SUBDIR ; get factures.csv" | grep NT_STATUS_OBJECT_NAME_NOT_FOUND ; then
    echo "factures.csv not found" 1>&2
    echo -n $(date '+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
    echo "ERREUR le fichier factures.csv n'a pas été trouvé sur le répertoire de partage ($0)" >> $TMP/$SAGE_EMAILFILE
    echo "DIAGNOSTIQUE: le transfer des fichiers VINSI ne semble pas s'être bien passé lors de l'etape exportSage" >> $TMP/$SAGE_EMAILFILE
    sendEmail
    exit 5
fi
if test "$SAMBA_SAGEVERIFY" && ! smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGEEXP_SUBDIR ; ls  $VINSIEXPORT" | grep "NT_STATUS_NO_SUCH_FILE" ; then
    echo "$VINSIEXPORT should not be present" 1>&2
    echo -n $(date'+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
    echo "ERREUR IMPORT SAGE (le fichier $VINSIEXPORT ne devrait pas être present)" >> $TMP/$SAGE_EMAILFILE
    echo "DIAGNOSTIQUE: l'import de SAGE ne s'est pas executé ou executé de manière partiel. Vérifiez l'état de SAGE, supprimez les eventuels imports (les factures seront reexportées au prochain export) puis supprimez le fichier $VINSIEXPORT sur le serveur de fichier" >> $TMP/$SAGE_EMAILFILE
    sendEmail
    exit 3
fi


echo -n $(date '+%d/%m/%Y %H:%M')" : " >> $TMP/$SAGE_EMAILFILE
echo $(cut -d ';' -f 14 $TMP/factures.csv | sort | uniq | wc -l)" facture(s) importée(s) sans erreur " >> $TMP/$SAGE_EMAILFILE
echo $(cut -d ';' -f 14 $TMP/societes.csv | sort | uniq | wc -l)" societe(s) mise(s) à jour sans erreur " >> $TMP/$SAGE_EMAILFILE

cd -
mkdir -p $PDFDIR/csv
cp $TMP/factures.csv $PDFDIR/csv/$(date '+%Y%m%d')_factures.csv
cp $TMP/societes.csv $PDFDIR/csv/$(date '+%Y%m%d')_societes.csv
cp $TMP/societes.csv $PDFDIR/csv/societes.last.csv

bash bin/exportFacturePDF.sh "$PDFDIR"/csv/"$(date '+%Y%m%d')"_factures.csv

cat $TMP/factures.csv | awk -F ';' '{print $14}' | sort | uniq | grep FACTURE- | while read FACTUREID; do
    php symfony facture:setexported $FACTUREID;
done

sendEmail

smbclient //$SAMBA_IP/$SAMBA_SHARE -A $SAMBA_AUTH -c "cd $SAMBA_SAGEEXP_SUBDIR ; rm factures.csv ; rm societes.csv"
