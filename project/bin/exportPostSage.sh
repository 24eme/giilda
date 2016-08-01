#!/bin/bash

. bin/config.inc

cat $1 | awk -F ';' '{print $14}' | sort | uniq | grep 2[0-9][0-9][0-9] | while read FACTUREID; do
    php symfony facture:setexported --application=declaration --env=$SYMFONYENV  $FACTUREID;
done

tail -n +2 $1 >> web/export/bi/export_bi_factures.csv
