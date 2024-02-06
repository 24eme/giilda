#! /bin/bash

if [ $# -ne 3 ]; then
    echo "Arguments manquants. Usage : $0 fichier periode appellation"
    exit 1
fi

function appellation() {
    case $1 in
        pinotgris)
            echo "declaration/certifications/AOC_ALSACE/genres/TRANQ/appellations/ALSACEBLANC/mentions/DEFAUT/lieux/DEFAUT/couleurs/DEFAUT/cepages/PG/reserve_interpro"
            ;;
        gewurztraminer)
            echo "declaration/certifications/AOC_ALSACE/genres/TRANQ/appellations/ALSACEBLANC/mentions/DEFAUT/lieux/DEFAUT/couleurs/DEFAUT/cepages/GW/reserve_interpro"
            ;;
    esac
}

FICHIER=$1
PERIODE=$2
APPELLATION=$3

LOGFILE="/tmp/reserve.$APPELLATION.log"
UPDFILE="./.updated.$APPELLATION.log"

touch "$LOGFILE" "$UPDFILE"

sed 1d "$FICHIER" | while read -r line; do
    CVI=$(echo "$line" | cut -d";" -f2)
    VALUE=$(echo "$line" | cut -d";" -f9)

    if [ -e "$UPDFILE" ] && grep -wq "$CVI" "$UPDFILE"; then
        echo "$CVI déjà à jour" >> "$LOGFILE"
        continue
    fi

    # Pour les SV, qui ont un CIVABA comme identifiant de DRM
    # TODO: à changer le check du déjà updated
    # CIVABA=$(grep "$CVI" /tmp/export_bi_etablissements.csv | cut -d';' -f7)
    # php symfony --application=civa document:setvalue "DRM-$CIVABA-$PERIODE" "$(appellation "$APPELLATION")" "$VALUE" 2>> "$LOGFILE"

    php symfony --application=civa document:setvalue "DRM-$CVI-$PERIODE" "$(appellation "$APPELLATION")" "$VALUE" 2>> "$LOGFILE"
done

grep "les valeurs suivantes ont été changés" "$LOGFILE" | cut -d'-' -f2 > "$UPDFILE"
