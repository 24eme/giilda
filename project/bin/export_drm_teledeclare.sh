. bin/config.inc

PRODUIT=$1
EXPORT_PATH=$2

if ! test "$PRODUIT"; then
    echo "Il manque le nom du produit à filtrer en 1er argument"
    exit;
fi

if ! test "$EXPORT_PATH"; then
    echo "Il manque le chemin de stockage des fichiers CSV des DRM en 2ème argument"
    exit;
fi

echo -n > $TMP/drms

curl -s http://$COUCHHOST:$COUCHPORT/$COUCHBASE/_changes | grep "\"DRM-" | cut -d "," -f 2 | sed 's/"id":"//' | sed 's/"//' | while read id
do
    curl -s http://$COUCHHOST:$COUCHPORT/$COUCHBASE/$id | jq -c '[._id,.teledeclare,.valide.date_signee,.declarant.no_accises]' | sed 's/\[//' | sed 's/\]//' | sed 's/"//g' | grep "true" | sed 's/null//' >> $TMP/drms
done

cat $TMP/drms| while read ligne
do
    ID=$(echo $ligne | cut -d "," -f 1)
    DATE=$(echo $ligne | cut -d "," -f 3 | sed 's/-//g')
    NOACCISES=$(echo $ligne | cut -d "," -f 4)
    PERIODE=$(echo $ID | cut -d "-" -f 3)
    DRMFILETMP=$TMP/$id$(date +%Y%m%d%H%M%S)
    php symfony drm:export-csv $ID $SYMFONYTASKOPTIONS | grep $PRODUIT > $DRMFILETMP
    if test $(cat $DRMFILETMP | grep -E "^CAVE;" | grep $PRODUIT | wc -l | sed -r 's/^0$//'); then
        echo $ID
        mkdir -p $EXPORT_PATH 2> /dev/null
        cp $DRMFILETMP $EXPORT_PATH/"$DATE"_"$PERIODE"_"$NOACCISES"_"$ID".csv
    fi
done
