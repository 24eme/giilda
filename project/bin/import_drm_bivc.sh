#!/bin/bash

. bin/config.inc

EXPORTDIR=data/drm_externe

mkdir $EXPORTDIR 2> /dev/null

echo "Récuperations des fichiers de BIVC"

curl -s $URLDRMBIVC | while read url
do
    FILENAME=$(echo -n $url | sed -r "s|^.+/(.+)$|\1|")
    if ! test -f "data/drm_externe/$FILENAME"; then
        echo $FILENAME
        curl -s $url > $EXPORTDIR/$FILENAME
    fi
done

echo "Import des fichiers"

ls $EXPORTDIR | grep -Ev "\.vinsi$" | awk -F '-' '{ version=99; if($4) { v=$4; gsub(".csv","",v); gsub("M", "", v); version=(version - v); } printf("%02d%d;%s\n", version, $3, $0) }' | sort | cut -d ";" -f 2 | while read csvfile
do
    csvfilevinsi=$csvfile".vinsi"
    cp $EXPORTDIR/$csvfile $EXPORTDIR/$csvfilevinsi
    sed -i 's/;suspendu;stocks_debut;initial;/;suspendu;stocks_debut;revendique;/' $EXPORTDIR/$csvfilevinsi
    sed -i 's/;suspendu;stocks_fin;final;/;suspendu;stocks_fin;revendique;/' $EXPORTDIR/$csvfilevinsi
    sed -i 's/;suspendu;sorties;ventefrancecrd;/;suspendu;sorties;ventefrancebouteillecrd;/' $EXPORTDIR/$csvfilevinsi
    sed -i 's/;suspendu;sorties;vracsanscontratacquitte;/;suspendu;sorties;vracsanscontrat;/' $EXPORTDIR/$csvfilevinsi
    sed -i 's/;suspendu;entrees;regularisation;/;suspendu;entrees;excedents;/' $EXPORTDIR/$csvfilevinsi
    sed -i 's/;suspendu;entrees;achatnoncrd;/;suspendu;entrees;revendique;/' $EXPORTDIR/$csvfilevinsi
    sed -i 's/;suspendu;entrees;recolte;/;suspendu;entrees;revendique;/' $EXPORTDIR/$csvfilevinsi
    sed -i 's/;Autres pays;/;suspendu;entrees;revendique;/' $EXPORTDIR/$csvfilevinsi

    periode=$(echo -n $csvfile | cut -d "_" -f 2)
    noaccises=$(cat $EXPORTDIR/$csvfilevinsi | head -n 1 | cut -d ";" -f 4)
    cvi=$(cat $EXPORTDIR/$csvfilevinsi | head -n 1 | cut -d ";" -f 3 | grep -Eo "\(.+\)" | sed 's/[()]//g')

    if ! test $noaccises; then
        noaccises="0"
    fi

    echo -n "$csvfilevinsi : "

    php symfony drm:edi-import $EXPORTDIR/$csvfilevinsi $periode "$noaccises" "$cvi"

done
