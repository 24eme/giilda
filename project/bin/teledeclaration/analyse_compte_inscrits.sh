#!/bin/bash

echo "Recupération des societes"

#curl -s $1"/_design/societe/_view/all" > ./all_societe

echo "Fin Recupération des societes"

echo "Recupération des ID societes"

cat ./all_societe | grep "ACTIF" | sed 's/{"id":"SOCIETE-//g' | cut -d '"' -f 1 > ./all_societe_id
echo "Fin Recupération des ID societes"

SEDCOMPTE='sed -r '"'"'s|([0-9]{6})|curl -s "'$1'/COMPTE-\101"|g'"'"

echo 'cat all_societe_id | '$SEDCOMPTE > ./extract_compte_principal.sh

#bash ./extract_compte_principal.sh > ./exec_compte_principal.sh

#bash ./exec_compte_principal.sh > ./exctracted_compte_principal

cat ./exctracted_compte_principal | grep 'teledeclaration_active":true' | sed 's/{"_id"://g' | cut -d "," -f 1 | sed 's/"COMPTE-//g' | sed 's/"//g' > compte_teledeclaration_active

