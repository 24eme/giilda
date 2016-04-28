#!/bin/bash

cat $1 | awk -F ';' '{print $14}' | sort | uniq | while read FACTUREID; do
    php symfony facture:setexported $FACTUREID;
done

