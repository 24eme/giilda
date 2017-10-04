#!/bin/bash

. $(dirname $0)/config.inc

DATE=$(date +%Y%m%d%H%M%S)
mkdir -p $TMP"/retoursDouanes" > /dev/null
LOGFILE=$TMP"/retoursDouanes/retrieveXMLAndCompare_"$DATE".log"

bash $(dirname $0)/retrieveXMLAndCompare.sh > $LOGFILE


RAPPORTBODY=$TMP"/retoursDouanes/retrieveXMLAndCompare_rapport_"$DATE".txt"
NBXMLIDENTIQUES=`cat $LOGFILE | grep "XML sont identiques" | wc -l`
NBXMLDIFFERENTS=`cat $LOGFILE | grep "XML differents" | wc -l`
NBXMLNONTRANSMIS=`cat $LOGFILE | grep -C 1 "n'a pas été transmise aux douanes" | grep "XML differents" | wc -l`
NBDRMDOUANEABSENTE=`cat $LOGFILE | grep "n'a pas été trouvée" | wc -l`
NBNUMACCISEMALDRM=`cat $LOGFILE | grep "Le numéro d'accise" | cut -d ":" -f 3 | sed "s/ Le numéro d'accise //" | sed "s/ ne correspond pas a celui de l'établissement (/;/g" | sed "s/ | /;/" | sed "s/)//" | wc -l`
NBNUMACCISEMAL=`cat $LOGFILE | grep "Le numéro d'accise" | cut -d ":" -f 3 | sed "s/ Le numéro d'accise //" | sed "s/ ne correspond pas a celui de l'établissement (/;/g" | sed "s/ | /;/" | sed "s/)//" | sort | uniq | wc -l`
NBDRMMODNONOUVERTE=`cat $LOGFILE | grep  "modificatrice non" | grep "DRM Suivante" | wc -l`


echo -e "XML identiques = "$NBXMLIDENTIQUES"\n\n" > $RAPPORTBODY;
echo -e "XML différents = "$NBXMLDIFFERENTS"\n" >> $RAPPORTBODY;
echo -e " (dont "$NBXMLNONTRANSMIS" qui n'ont pas été transmis aux douanes)\n" >> $RAPPORTBODY;

echo -e "\n DRM pour lesquelles il faudra ouvrir une modificatrice : \n " >> $RAPPORTBODY;
cat $LOGFILE | grep -C 1 "DRM modificatrice ouverte" | grep "XML differents" | cut -d ' ' -f 1 | sed -r "s|DRM-([0-9]+)-([0-9]+)|$URLDRMINTERNE\1\/visualisation\/\2|" >> $RAPPORTBODY;

echo -e "\nNombre de modificatrice qui ne seront pas ouverte car drm suivante = "$NBDRMMODNONOUVERTE >> $RAPPORTBODY;

echo -e "\n\nDRM non trouvées sur la plateforme mais existantes sur CIEL = "$NBDRMDOUANEABSENTE" (les identifiants existent sur la plateforme)" >> $RAPPORTBODY;

echo -e "\n\nIdentifiants problématiques :\n\n" >> $RAPPORTBODY;
cat $LOGFILE | grep "n'a pas été trouvée" | sed -r 's/(.*)La DRM de (.+) (.+)/\2/g' | cut -d ' ' -f 1 | sort | uniq | sed -r "s|(.*)|$URLDRMINTERNE\1|g" >> $RAPPORTBODY;

echo -e "\n\nNuméro d'accise mal référencé = "$NBNUMACCISEMALDRM" DRM, "$NBNUMACCISEMAL" accises :\n\n" >> $RAPPORTBODY;

cat $LOGFILE | grep "Le numéro d'accise" | cut -d ":" -f 3 | sed "s/ Le numéro d'accise //" | sed "s/ ne correspond pas a celui de l'établissement (/;/g" | sed "s/ | /;/" | sed "s/)//" | sort | uniq | sed -r "s|(FR.+);(.+);(.*)|$URLDRMINTERNE\2 \1 (obtenu des douanes) sur la plateforme : \3|g" >> $RAPPORTBODY;

mail -s "[RAPPORT RETOUR DOUANE X] du $DATE" "adresse@adresse.com" < $RAPPORTBODY;
