#!/bin/bash

. bin/config.inc

csv=$1

if ! test "$csv" ; then 
    php symfony export:facture > /tmp/factures.csv
    csv=/tmp/factures.csv
fi

awk -F ';' '{print $2";"$14}' $csv  | grep FACTURE | sort | uniq  | while read datefacture ; 
do
        facture=$(echo $datefacture | sed 's/.*;//')
        date=$(echo $datefacture | sed 's/;.*//')
	FACTUREDIR=$PDFDIR"/"$(echo $date  | sed 's/\(....\)-\(..\)-../\1\/\2/');
	mkdir -p $FACTUREDIR 2> /dev/null;
	php symfony generate:AFacture --directory=$FACTUREDIR $facture ; 
done

mkdir -p $PDFDIR"/export"
annee=$(date '+%Y')
php symfony export:facture-annee-comptable $annee"-01" $annee"-12" > $PDFDIR"/export/"$annee"_export_comptable.csv.tmp"
mv $PDFDIR"/export/"$annee"_export_comptable.csv.tmp" $PDFDIR"/export/"$annee"_export_comptable.csv"

if test $SAMBA_FACTURELOCALDIR; then
    sudo mount $SAMBA_FACTURELOCALDIR
    rsync -a $PDFDIR $SAMBA_FACTURELOCALDIR
    sudo umount $SAMBA_FACTURELOCALDIR
fi
