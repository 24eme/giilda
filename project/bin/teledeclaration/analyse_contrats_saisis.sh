#!/bin/bash

echo "Recupération des contrats"

curl -s $1"/_design/vrac/_view/soussigneidentifiant?reduce=false&startkey=%5B%22SOCIETE%22%5D&endkey=%5B%22SOCIETE%22,%5B%5D%5D" > ./all_contrats

echo "Fin Recupération des contrats"


cat ./all_contrats | sed 's/{"id"://g' | sed 's/"key":\[//g' | sed 's/\],"value":\[/,/g' | sed 's/\]},//g' > ./all_contrats_csv_raw

cat ./all_contrats_csv_raw | cut -d "," -f 1,4,7,12,14,16,23,24,25 | grep -vE "(800563|800564|800565)" > ./all_contrats_signature.csv

cat ./all_contrats_signature.csv | sort -t ',' -k 1,1 -u > ./all_contrats_signature.uniq.csv

cat ./all_contrats_signature.uniq.csv | grep -E "ATTENTE_SIGNATURE" > ./contrats_attente_signature.csv
cat ./all_contrats_signature.uniq.csv | grep -E "BROUILLON" > ./contrats_brouillon.csv
cat ./all_contrats_signature.uniq.csv | grep -E "(SOLDE|NONSOLDE)" | grep -E "([0-9]{4}-[0-9]{2}-[0-9]{2}\ [0-9]{2}:[0-9]{2}:[0-9]{2}+)" > ./contrats_signes.csv

