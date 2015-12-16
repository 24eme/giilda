#!/bin/bash

. bin/config.inc

REMOTE_DATA=$1

SYMFODIR=$(pwd);
DATA_DIR=$TMP/data_ivbd_csv

if test "$1"; then
    echo "Récupération de l'archive"
    scp $1 $TMP/data_ivbd.tgz
    
    echo "Désarchivage"
    rm -rf $TMP/data_ivbd_origin
    mkdir $TMP/data_ivbd_origin
    cd $TMP/data_ivbd_origin
    tar -zxvf $TMP/data_ivbd.tgz

    rm $TMP/data_ivbd.tgz

    cd $SYMFODIR

    echo "Conversion des fichiers en utf8"
    
    rm -rf $DATA_DIR
    mkdir -p $DATA_DIR

    ls $TMP/data_ivbd_origin/IVBD | while read ligne  
    do
        echo $TMP/data_ivbd_origin/IVBD/$ligne
        iconv -f utf-16 -t utf-8 $TMP/data_ivbd_origin/IVBD/$ligne > $DATA_DIR/$ligne
    done

    rm -rf $TMP/data_ivbd_origin
fi

