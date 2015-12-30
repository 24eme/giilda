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

echo "Import de la configuration"

curl -sX DELETE "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/CONFIGURATION"?rev=$(curl -sX GET "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/CONFIGURATION" | grep -Eo '"_rev":"[a-z0-9-]+"' | sed 's/"//g' | sed 's/_rev://')
php symfony import:configuration CONFIGURATION data/import/configuration/ivso
php symfony cc > /dev/null

cat $DATA_DIR/produits.csv | tr -d '\r' | awk -F ";" '{ print $5 ";" $4 }' | sort -t ";" -k 1,1 | sed 's/IGP Lot Blanc/IGP Côte du Lot Blanc/' | sed 's/IGP Lot Rouge/IGP Côte du Lot Rouge/' | sed 's/IGP Lot Rosé/IGP Côte du Lot Rosé/' | sed 's/IGP Tarn/IGP Côtes du Tarn/' | sed 's/AOP Pacherenc du Vic Bilh Moelleux/AOP Pacherenc du Vic Bilh Blanc Moelleux/' | sed 's/Côtes du Brulhois/Brulhois/' | sed 's/AOP Gaillac  Blanc sec - Premières cotes/AOP Gaillac Premières côtes Blanc sec/' | sed 's/AOP Gaillac Blanc Effervescent/AOP Gaillac Mousseux/' | sed 's/AOP Gaillac Doux - Vendanges tardives/AOP Gaillac Blanc doux Vendanges tardives/' | sed 's/AOP Entraygues et Fel/AOP Entraygues - Le Fel/' | sed 's/IGP Terroir Landais/IGP Landes/' | sed 's/AOP Lavilledieu/IGP Lavilledieu/' | sed 's/IGP Bigorre/IGP Comté Tolosan Bigorre/' | sed 's/IGP Côtes du Condomois/IGP Côtes de Gascogne Condomois/' | sed 's/IGP Côtes du Tarn et Garonne/IGP Comté Tolosan Tarn et Garonne/' | sed 's/IGP Ctx et Terrasse de Montauban/IGP Comté Tolosan Coteaux et Terrasses de Montauban/' | sed 's/IGP Pyrénées Atlantiques/IGP Comté Tolosan Pyrénées Atlantiques/' | sed 's/IGP Cantal/IGP Comté Tolosan Cantal/' | sed 's/IGP Coteaux de Glanes Blanc Sec/IGP Coteaux de Glanes Blanc/' > $DATA_DIR/produits_conversion.csv
cat $DATA_DIR/cepages.csv | cut -d ";" -f 2,3 | sort -t ";" -k 1,1 > $DATA_DIR/cepages.csv.sorted

echo "Import des contacts"

#Affichage des entêtes en ligne
#head -n 1 /tmp/giilda/data_ivso_csv/contacts_extravitis.csv | tr ";" "\n" | awk -F ";" 'BEGIN { nb=0 } { nb = nb + 1; print nb ";" $0 }'

cat $DATA_DIR/contacts_extravitis.csv | tr -d '\r' | awk -F ';' '
function ltrim(s) { sub(/^[ \t\r\n]+/, "", s); return s } function rtrim(s) { sub(/[ \t\r\n]+$/, "", s); return s } function trim(s)  { return rtrim(ltrim(s)); } { 
famille="AUTRE" ; 
famille=($13 ? "VITICULTEUR" : famille ) ; 
famille=($14 ? "NEGOCIANT" : famille ) ; 
famille=($15 ? "COURTIER" : famille ) ; 
statut=($37 == "Oui" ? "SUSPENDU" : "ACTIF") ; 
print $1 ";" famille ";" trim($2 " " $3 " " $4) ";;" statut ";;" $34 ";;;" $5 ";" $6 ";" $7 ";;" $9 ";" $10 ";" $12 ";FR;" $19 ";" $16 ";;" $18 ";" $17 ";" $20 ";" 
}' > $DATA_DIR/societes.csv

cat $DATA_DIR/contacts_extravitis.csv | tr -d '\r' | awk -F ';' '
function ltrim(s) { sub(/^[ \t\r\n]+/, "", s); return s } function rtrim(s) { sub(/[ \t\r\n]+$/, "", s); return s } function trim(s)  { return rtrim(ltrim(s)); } { 
nom=trim($2 " " $3 " " $4) ; 
famille="AUTRE" ; 
famille=($13 ? "VITICULTEUR" : famille ) ; 
famille=($14 ? "NEGOCIANT" : famille ) ; 
famille=($15 ? "COURTIER" : famille ) ; 
statut=($37 == "Oui" ? "SUSPENDU" : "ACTIF") ; 
nom=nom ; 
if (famille == "AUTRE") next ; 
print $1 ";" $1 ";" famille ";" nom ";" statut ";HORS_REGION;" $27 ";;;;" $5 ";" $6 ";" $7 ";;" $9 ";" $10 ";" $12 ";FR;" $19 ";" $16 ";;" $18 ";" $17 ";" $20 ";" 
}' > $DATA_DIR/etablissements.csv

php symfony import:societe $DATA_DIR/societes.csv
php symfony import:etablissement $DATA_DIR/etablissements.csv

echo "Import des contrats"

cat $DATA_DIR/contrats.csv | tr -d "\n" | tr "\r" "\n" | sort -t ";" -k 16,16 > $DATA_DIR/contrats.csv.sorted.produits

join -a 1 -t ";" -1 16 -2 1 $DATA_DIR/contrats.csv.sorted.produits $DATA_DIR/produits_conversion.csv > $DATA_DIR/contrats_produits.csv

cat $DATA_DIR/contrats_produits.csv | sort -t ";" -k 24,24 > $DATA_DIR/contrats_produits.sorted.cepages

join -a 1 -t ";" -1 24 -2 1 $DATA_DIR/contrats_produits.sorted.cepages $DATA_DIR/cepages.csv.sorted > $DATA_DIR/contrats_produits_cepages.csv

# /!\ Transformation arbitraire de la ligne ou l'année est 211 => 2011, 22012 => 2012, 201 => 2015, 20112 => 2012
cat $DATA_DIR/contrats_produits_cepages.csv | sed 's/;4;10222;10222;211;/;4;10222;10222;2011;/g' | sed 's/;3;10194;10194;22012;/;3;10194;10194;2012;/g' | sed 's/;2;10849;10849;201;12;91236;/;2;10849;10849;2015;12;91236;/g' | sed 's/;88;7922;7922;20112;/;88;7922;7922;2012;/g' > $DATA_DIR/contrats_produits_cepages.clean.csv

# Début génération des Id couchDB

cat $DATA_DIR/contrats_produits_cepages.clean.csv | sed -r 's/([0-9]*);([0-9]*);([0-9]*);([0-9]*);([0-9]{2});(.*)/\1;\2;\3;\4;20\5;\6/g' | awk -F ';' '{ 
date_signature=gensub(/^([0-9]+)-([0-9]+)-([0-9]+)$/,"\\3-\\1-\\2", 1, $9); 
date_saisie=gensub(/^([0-9]+)-([0-9]+)-([0-9]+)$/,"\\3-\\1-\\2", 1, $11); 
num_bordereau=$7;
if(length($7) > 7){
   num_bordereau=substr($7,1,7);
}
id_vrac=sprintf("%4d%07d", $5 , num_bordereau);
libelle_produit=$41; 
vin_bio=$19;
vin_prepare=$20;
caracteristiques_vins=""
if(vin_bio=="O"){
  caracteristiques_vins="agriculture_biologique";
}
if(vin_prepare=="O"){
  caracteristiques_vins=caracteristiques_vins "" (length(caracteristiques_vins))? ",vin_prepare" : "vin_prepare" ;
}

cle_delais_paiement="";
libelle_delais_paiement=$33;
if(libelle_delais_paiement=="Comptant"){
  cle_delais_paiement="COMPTANT";
}else if(libelle_delais_paiement=="60 jours à compter de l'"'"'émission de la facture"){
  cle_delais_paiement="60_JOURS";
}else if(libelle_delais_paiement=="Délai prévu par accord professionnel"){
  cle_delais_paiement="ACCORD_INTERPROFESSIONNEL";
}else if(libelle_delais_paiement=="45 jours à compter du mois d'"'"'émission de la facture"){
  cle_delais_paiement="45_JOURS";
}

print $4 ";" id_vrac ";" num_bordereau ";"  date_signature ";" date_saisie ";VIN_VRAC;" $12 ";;;" $13 ";" $14 ";" $2 ";" libelle_produit ";" $17 ";" $1 ";" $42 ";;;" $21 ";hl;" $23 ";;;" $21 ";" $22 ";" $24 ";" $24 ";" cle_delais_paiement ";" $33 ";" $32 ";;;;100_ACHETEUR;" $26 ";" $28 ";;" $30 ";" caracteristiques_vins
}' | sort > $DATA_DIR/vracs.csv.tmp


cat $DATA_DIR/vracs.csv.tmp | awk -F ';' 'BEGIN { id_vrac_prec=0; num_incr=1; num_incr_aux=1; } {
  id_vrac=$2;
  num_bordereau=$3;
if(id_vrac_prec==id_vrac) {
  if(num_bordereau=="0"){
    num_bordereau=sprintf("9%06d",num_incr);
    num_incr=num_incr+1;
  }else{
    num_bordereau=sprintf("%1d%06d",num_incr_aux,substr($3,2,6));
    num_incr_aux=num_incr_aux+1;
  }  
}else{
  if(num_bordereau=="0"){
    num_bordereau=sprintf("9%06d",num_incr);
    num_incr=num_incr+1;

if(length(num_bordereau) > 7){
print num_bordereau;
}
  }
  num_incr_aux=1;
}
id_vrac=substr($2,0,4) "" sprintf("%07d",num_bordereau);  
print $1 ";" id_vrac ";" num_bordereau ";" $0
id_vrac_prec=$2;
}' | sed -r 's/^([0-9]*);([0-9]*);([0-9]*);([0-9]*);([0-9]*);([0-9]*);(.*)/\1;\2;\7/g' | sed 's/^Numéro Contrat;   00000000;Numéro ;//g' | sed 's/;   00000000//g' > $DATA_DIR/vracs.csv

php symfony import:vracs $DATA_DIR/vracs.csv --env="ivso"

echo "Import des DRM"

cat $DATA_DIR/DRM.csv | tr -d "\r" | sort -t ";" -k 6,6 > $DATA_DIR/drm.csv.produits.sorted

join -a 1 -t ";" -1 6 -2 1  $DATA_DIR/drm.csv.produits.sorted $DATA_DIR/produits_conversion.csv | sort -t ";" -k 2,3 > $DATA_DIR/drm_produits.csv

cat $DATA_DIR/drm_produits.csv | awk -F ';' '{
identifiant=sprintf("%06d01", $4);
base="CAVE;" $5 ";" identifiant ";;" $37 ";;;;;;;" ; 
print base "stocks_debut;revendique;" $10 ; 
# print base "entrees;recolte;" $11 ;  #récolte
if($12 > 0) { print base "entrees;revendique;" $12+0 ; } #volume agréé
if($13 > 0) { print base "entrees;declassement;" $13+0 ; } #declassement
if($13 < 0) { print base "sorties;declassement;" ($13+0)*-1 ; } #declassement
if($14 > 0) { print base "sorties;destructionperte;" $14+0 ; } #perte
if($15 > 0) { print base "sorties;distillationusageindustriel;" $15+0 ; } #lie_et_mouts
if($16 > 0) { print base "sorties;distillationusageindustriel;" $16+0 ; } #usages_industriels
if($17 > 0) { print base "sorties;ventefrancebouteillecrd;" $17+0 ; } #collective_ou_individuelle
if($18 > 0) { print base "sorties;vracsanscontratsuspendu;" $18+0 ; } #dsa_dsac
if($18 < 0) { print base "entrees;regularisation;" ($18+0)*-1 ; } #dsa_dsac
if($19 > 0) { print base "sorties;vracsanscontratsuspendu;" $19+0 ; } #facture_etc
if($19 < 0) { print base "entrees;regularisation;" ($19+0)*-1 ; } #dsa_dsac
if($20 > 0) { print base "sorties;vracsanscontratsuspendu;" $20 ; } #france_sans_contrat
if($20 < 0) { print base "entrees;regularisation;" ($20+0)*-1 ; } #dsa_dsac
# if($21 > 0) { print base "sorties;vrac;" $21+0 ; } #france_sous_contrat
if($22 > 0) { print base "sorties;export;" $22+0 ";UE" ; }  #expedition_ue
if($23 > 0) { print base "sorties;export;" $23+0 ";HORS UE" ; } #expedition_hors_ue
if($24 > 0) { print base "sorties;travailafacon;" $24+0 ; } #relogement
print base "stocks_fin;revendique;" $25+0 ;
# print base "stocks?;dont_volume_bloque;" $26+0 ; #dont_volume_bloque
# print base "stocks?;quantite_gagees;" $27+0 ; #quantite_gagees
}' > $DATA_DIR/drm_cave.csv

cat $DATA_DIR/DRM_Factures.csv | tr -d "\r" | sort -t ";" -k 5,5 > $DATA_DIR/drm_factures.csv.produits.sorted

join -a 1 -t ";" -1 5 -2 1  $DATA_DIR/drm_factures.csv.produits.sorted $DATA_DIR/produits_conversion.csv > $DATA_DIR/drm_factures_produits.csv

cat $DATA_DIR/drm_factures_produits.csv | awk -F ';' '{
if (!$10 || $10 == "INCONNU") { next }
identifiant=sprintf("%06d01", $17);
base="CAVE;" $5 ";" identifiant ";;" $45 ";;;;;;;" ; 
numero_contrat=gensub(/-/, "00", 1, $10);
print base "sorties;vrac;" $21+0 ";;" numero_contrat ; 
}' > $DATA_DIR/drm_cave_contrats.csv

cat $DATA_DIR/drm_cave.csv $DATA_DIR/drm_cave_contrats.csv | sort -t ";" -k 2,3  | grep -E "^[A-Z]+;(2012(08|09|10|11|12)|2013[0-1]{1}[0-9]{1}|2014[0-1]{1}[0-9]{1}|2015[0-1]{1}[0-9]{1});" > $DATA_DIR/drm.csv

echo -n > $TMP/drm_lignes.csv

cat $DATA_DIR/drm.csv | while read ligne  
do
    if [ "$PERIODE" != "$(echo $ligne | cut -d ";" -f 2)" ] || [ "$IDENTIFIANT" != "$(echo $ligne | cut -d ";" -f 3)" ]
    then

        if [ $(cat $TMP/drm_lignes.csv | wc -l) -gt 0 ]
        then
            php symfony drm:edi-import $TMP/drm_lignes.csv $PERIODE $IDENTIFIANT --trace
        fi

        echo -n > $TMP/drm_lignes.csv

    fi
    PERIODE=$(echo $ligne | cut -d ";" -f 2)
    IDENTIFIANT="$(echo $ligne | cut -d ";" -f 3)"
    echo $ligne >> $TMP/drm_lignes.csv
done