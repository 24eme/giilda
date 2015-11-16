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

cat $DATA_DIR/producteurs.csv | cut -d ";" -f 2 | grep -E "^[0-9]+" | sed 's/$/;VITICULTEUR/' > $DATA_DIR/producteurs_ids
cat $DATA_DIR/negociant.csv | cut -d ";" -f 2 | grep -E "^[0-9]+" | sed 's/$/;NEGOCIANT/' > $DATA_DIR/negociants_ids
cat $DATA_DIR/courtier.csv | cut -d ";" -f 12 | grep -E "^[0-9]+" | sed 's/$/;COURTIER/' > $DATA_DIR/courtiers_ids
cat $DATA_DIR/producteurs_ids $DATA_DIR/negociants_ids $DATA_DIR/courtiers_ids | sort -t ";" -k 1,1 > $DATA_DIR/operateurs_ids_familles

cat $DATA_DIR/producteurs_produits.csv | sort -t ";" -k 2,2 > $DATA_DIR/producteurs_produits.sorted.csv

join -t ";" -v 2 -1 1 -2 2 $DATA_DIR/operateurs_ids_familles $DATA_DIR/producteurs_produits.sorted.csv | cut -d ";" -f 1 | grep -E "^[0-9]+" | sed 's/$/;AUTRE/' > $DATA_DIR/autres_ids

cat $DATA_DIR/producteurs_ids $DATA_DIR/negociants_ids $DATA_DIR/courtiers_ids $DATA_DIR/autres_ids | sort -t ";" -k 1,1 > $DATA_DIR/operateurs_ids_familles

join -t ";" -1 1 -2 2 $DATA_DIR/operateurs_ids_familles $DATA_DIR/producteurs_produits.sorted.csv | sort -t ";" -k 1,1 > $DATA_DIR/operateurs.csv

cat $DATA_DIR/operateurs.csv | awk -F ";" '{ print $1 ";" $2 ";" $5 ";;ACTIF;;" $15 ";;;" $6 ";" $7 ";" $8 ";;" $9 ";" $11 ";" $13 ";FR;" $19 ";" $16 ";;" $17 ";" $18 ";;"  }' > $DATA_DIR/societes.csv

php symfony import:societe $DATA_DIR/societes.csv

cat $DATA_DIR/operateurs.csv | grep -v ";AUTRE;" | awk -F ";" '{ print $1 ";" $1 ";" $2 ";" $5 ";ACTIF;HORS_REGION;" $4 ";;;;" $6 ";" $7 ";" $8 ";;" $9 ";" $11 ";" $13 ";FR;" $19 ";" $16 ";;" $17 ";" $18 ";;"  }' > $DATA_DIR/etablissements.csv

php symfony import:etablissement $DATA_DIR/etablissements.csv

echo "Import des contrats"

php symfony import:vracs $DATA_DIR/contrats.csv
