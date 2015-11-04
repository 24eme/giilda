#!/bin/bash

. bin/config.inc

REMOTE_DATA=$1

SYMFODIR=$(pwd);
DATA_DIR=$TMP/data_ivso_csv

if test "$1"; then
    echo "Récupération de l'archive"
    scp $1 $TMP/data_ivso.zip
    
    echo "Désarchivage"
    rm -rf $TMP/data_ivso_origin
    mkdir $TMP/data_ivso_origin
    cd $TMP/data_ivso_origin
    unzip $TMP/data_ivso.zip

    rm $TMP/data_ivso.zip

    rename 's/^Table //' *
    rename 's/ /_/' *
    rename 's/des_//' *

    cd $SYMFODIR

    echo "Conversion des fichiers en csv"
    
    rm -rf $DATA_DIR
    mkdir -p $DATA_DIR

    ls $TMP/data_ivso_origin | while read ligne  
    do
        CSVFILENAME=$(echo $ligne | sed 's/\.xlsx/\.csv/')
        echo $DATA_DIR/$CSVFILENAME
        xlsx2csv -d ";" $TMP/data_ivso_origin/$ligne > $DATA_DIR/$CSVFILENAME
    done

    rm -rf $TMP/data_ivso_origin
fi

echo "Import des contacts"

