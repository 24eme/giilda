#!/bin/bash

. bin/config.inc

php symfony export:facture > /tmp/factures.csv

awk -F ';' '{print $14}' /tmp/factures.csv  | grep FACTURE | sort | uniq  | while read facture ; 
do
	FACTUREDIR=$PDFDIR"/"$(echo $facture | cut -f 3 -d '-' | sed 's/\(....\)\(..\)..../\1\/\2/');
	mkdir -p $FACTUREDIR 2> /dev/null;
	php symfony generate:AFacture --directory=$FACTUREDIR $facture ; 
done

if test $SAMBA_FACTURELOCALDIR; then
    mount $SAMBA_FACTURELOCALDIR
    rsync -a $PDFDIR $SAMBA_FACTURELOCALDIR
    umount $SAMBA_FACTURELOCALDIR
fi
