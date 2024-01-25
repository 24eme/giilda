#! /bin/bash
#
# cut -d';' -f2 dr* | grep -v [A-Z] | sort | uniq | while read CVI; do grep "$CVI" * | sed 's/:/;/' | cut -d';' -f1,3,4,10 ; done
#
# Usage : update_reserve_interpro.sh dr|sv

if [ $# -ne 1 ]; then
    echo "Argument manquant. Usage : $0 dr|sv"
    exit 1
fi

function appellation() {
    case $1 in
        pinotgris)
            echo "PINOTGRIS"
            ;;
        gewurztraminer)
            echo "GW"
            ;;
        *)
            echo "rien"
            ;;
    esac
}

function value() {
    case $1 in
        dr)
            echo 9
            ;;
        sv)
            echo 8
            ;;
    esac
}

TYPE=$1
LIST_OF_CVIS=$(mktemp)

cut -d';' -f 2 "$TYPE"_* | grep -v "[A-Z]" | sort | uniq > "$LIST_OF_CVIS"

while read -r CVI; do
    APLFORCVI=$(mktemp)

    grep "$CVI" -- "$TYPE"*.csv | sed 's/:/;/' | cut -d'_' -f2 > "$APLFORCVI"

    while read -r APL; do
        VALUE=$(grep "$CVI" -- "$TYPE"*"$APL"*.csv | sed 's/:/;/' | cut -d';' -f "$(value "$TYPE")")
        echo "$CVI $APL $VALUE"
        # php symfony document:setvalue "DRM-$CVI-202401" appellation "$APL"
    done < "$APLFORCVI"

    rm "$APLFORCVI"

done < "$LIST_OF_CVIS"

rm "$LIST_OF_CVIS"
