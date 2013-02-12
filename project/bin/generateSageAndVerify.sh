#!/bin/bash

bash bin/exportSage.sh > /tmp/export.sage

echo -n "Nombre de ligne d'écriture : "
wc -l /tmp/factures.csv | sed 's/ .*//'
echo -n "Somme des lignes de débit : "
debit=$(echo $(cat /tmp/factures.csv | grep DEBIT | awk -F ';' '{print $11}' | grep [0-9] | sed 's/$/ + /')"0"  | bc)
echo $debit
echo -n "Somme des lignes de crédit : "
credit=$(echo $(cat /tmp/factures.csv | grep CREDIT | awk -F ';' '{print $11}' | grep [0-9] | sed 's/$/ + /')"0"  | bc)
echo $credit
echo -n "Résultat de l'équililbe : "
equilibre=$(echo $debit - $credit | bc)
echo $equilibre
