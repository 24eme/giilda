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
