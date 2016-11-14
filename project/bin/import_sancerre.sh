#!/bin/bash

. bin/config.inc

REMOTE_DATA=$1

SYMFODIR=$(pwd);
DATA_DIR=$TMP/data_sancerre_csv

if test "$REMOTE_DATA"; then
    echo "Récupération de l'archive"
    scp $REMOTE_DATA $TMP/data_sancerre.zip

    echo "Désarchivage"
    rm -rf $TMP/data_sancerre_origin
    mkdir $TMP/data_sancerre_origin
    cd $TMP/data_sancerre_origin
    unzip $TMP/data_sancerre.zip
    rm $TMP/data_sancerre.zip

    cd $SYMFODIR

    echo "Conversion des fichiers en utf8"

    rm -rf $DATA_DIR
    mkdir -p $DATA_DIR

    file -i $TMP/data_sancerre_origin/*.XML | grep -E "(iso-8859-1|unknown-8bit|us-ascii)" | cut -d ":" -f 1 | sed -r 's|^.+/||' | while read ligne
    do
        echo $TMP/data_sancerre_origin/$ligne
        iconv -f iso-8859-1 -t utf-8 $TMP/data_sancerre_origin/$ligne | tr -d "\r"  > $TMP/data_sancerre_origin/$(echo $ligne | sed 's/.XML/.utf8.XML/')
    done
fi
