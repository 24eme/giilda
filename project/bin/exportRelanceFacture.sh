#!/bin/bash

. bin/config.inc

php symfony export:facture-relances $SYMFONYTASKOPTIONS --factureid=FACTURE-XXXXX-YYYY --env=prod > $TMP/$(date +%Y%m%d_relances.csv)
php symfony export:facture-relances $SYMFONYTASKOPTIONS --entete=0 --env=prod >> $TMP/$(date +%Y%m%d_relances.csv)

PDFDIR=$TMP/pdf/
mkdir -p $PDFDIR

php symfony facture:generate-relance-pdf $SYMFONYTASKOPTIONS --filename="$(date +%Y%m%d_premiere_relance.pdf)" --directory=$PDFDIR $TMP/$(date +%Y%m%d_relances.csv) 1
php symfony facture:generate-relance-pdf $SYMFONYTASKOPTIONS --filename="$(date +%Y%m%d_derniere_relance.pdf)" --directory=$PDFDIR $TMP/$(date +%Y%m%d_relances.csv) 2

cat $TMP/$(date +%Y%m%d_relances.csv) | grep "FACTURE-" | while read ligne; do
    FACTUREID=$(echo -n $ligne | cut -d ';' -f 18)
    php symfony facture:addrelance $SYMFONYTASKOPTIONS $FACTUREID;
done

if [ -e $PDFDIR/$(date +%Y%m%d_premiere_relance.pdf) ]
then
    echo "$PDFDIR/$(date +%Y%m%d_premiere_relance.pdf)|$(date +%Y%m%d_premiere_relance.pdf)|Courriers de première relance"
fi

if [ -e $PDFDIR/$(date +%Y%m%d_derniere_relance.pdf) ]
then
    echo "$PDFDIR/$(date +%Y%m%d_derniere_relance.pdf)|$(date +%Y%m%d_derniere_relance.pdf)|Courriers de dernière relance"
fi
