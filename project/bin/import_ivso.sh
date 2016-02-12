#!/bin/bash

. bin/config.inc

REMOTE_DATA=$1

SYMFODIR=$(pwd);
DATA_DIR=$TMP/data_ivso_csv

if test "$REMOTE_DATA"; then
    echo "Récupération de l'archive"
    scp $REMOTE_DATA $TMP/data_ivso.zip
    
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

cat $DATA_DIR/produits.csv | tr -d '\r' | awk -F ";" '{ print $5 ";" $4 }' | sort -t ";" -k 1,1 | sed 's/IGP Lot Blanc/IGP Côte du Lot Blanc/' | sed 's/IGP Lot Rouge/IGP Côte du Lot Rouge/' | sed 's/IGP Lot Rosé/IGP Côte du Lot Rosé/' | sed 's/IGP Tarn/IGP Côtes du Tarn/' | sed 's/AOP Pacherenc du Vic Bilh Moelleux/AOP Pacherenc du Vic Bilh Blanc Moelleux/' | sed 's/Côtes du Brulhois/Brulhois/' | sed 's/AOP Gaillac  Blanc sec - Premières cotes/AOP Gaillac Premières côtes Blanc sec/' | sed 's/AOP Gaillac Blanc Effervescent/AOP Gaillac Mousseux/' | sed 's/AOP Gaillac Doux - Vendanges tardives/AOP Gaillac Blanc doux Vendanges tardives/' | sed 's/AOP Entraygues et Fel/AOP Entraygues - Le Fel/' | sed 's/IGP Terroir Landais/IGP Landes/' | sed 's/AOP Lavilledieu/IGP Lavilledieu/' | sed 's/IGP Bigorre/IGP Comté Tolosan/' | sed 's/IGP Côtes du Condomois/IGP Côtes de Gascogne/' | sed 's/IGP Côtes du Tarn et Garonne/IGP Comté Tolosan/' | sed 's/IGP Ctx et Terrasse de Montauban/IGP Comté Tolosan/' | sed 's/IGP Pyrénées Atlantiques/IGP Comté Tolosan/' | sed 's/IGP Cantal/IGP Comté Tolosan/' | sed 's/IGP Coteaux de Glanes Blanc Sec/IGP Coteaux de Glanes Blanc/' | sed 's/IGP Autres Vins de Pays/IGP/' | sed 's/IGP Lot et Garonne/IGP/' > $DATA_DIR/produits_conversion.csv
cat $DATA_DIR/cepages.csv | cut -d ";" -f 2,3 | sort -t ";" -k 1,1 > $DATA_DIR/cepages.csv.sorted

echo "Construction du fichier d'import des Contacts"

#Affichage des entêtes en ligne
#head -n 1 /tmp/giilda/data_ivso_csv/contacts_extravitis.csv | tr ";" "\n" | awk -F ";" 'BEGIN { nb=0 } { nb = nb + 1; print nb ";" $0 }'

cat $DATA_DIR/contacts_extravitis.csv | tr -d '\r' | awk -F ';' '
function ltrim(s) { sub(/^[ \t\r\n]+/, "", s); return s } function rtrim(s) { sub(/[ \t\r\n]+$/, "", s); return s } function trim(s)  { return rtrim(ltrim(s)); } { 
famille="AUTRE" ; 
famille=($13 ? "VITICULTEUR" : famille ) ; 
famille=($14 ? "NEGOCIANT" : famille ) ; 
famille=($15 ? "COURTIER" : famille ) ; 
statut=($37 == "Oui" ? "SUSPENDU" : "ACTIF") ; 
print sprintf("%06d", $1) ";" famille ";" trim($2 " " $3 " " $4) ";;" statut ";;" $34 ";;;" $5 ";" $6 ";" $7 ";;" $9 ";" $10 ";" $12 ";FR;" $19 ";" $16 ";;" $18 ";" $17 ";" $20 ";" 
}' | sed 's/;";/;;/g' > $DATA_DIR/societes.csv

cat $DATA_DIR/contacts_extravitis.csv | tr -d '\r' | awk -F ';' '
function ltrim(s) { sub(/^[ \t\r\n]+/, "", s); return s } function rtrim(s) { sub(/[ \t\r\n]+$/, "", s); return s } function trim(s)  { return rtrim(ltrim(s)); } { 
nom=trim($2 " " $3 " " $4) ; 
famille="AUTRE" ; 
famille=($13 ? "PRODUCTEUR" : famille ) ; 
famille=($14 ? "NEGOCIANT" : famille ) ; 
famille=($15 ? "COURTIER" : famille ) ; 
statut=($37 == "Oui" ? "SUSPENDU" : "ACTIF") ; 
nom=nom ; 
if (famille == "AUTRE") next ;
region="REGION_CVO";  
identifiant_societe=sprintf("%06d", $1);
identifiant=identifiant_societe "01";

print identifiant ";" identifiant_societe ";" famille ";" nom ";" statut ";" region ";" $27 ";;;;" $5 ";" $6 ";" $7 ";;" $9 ";" $10 ";" $12 ";FR;" $19 ";" $16 ";;" $18 ";" $17 ";" $20 ";" 
}' > $DATA_DIR/etablissements.csv

echo "Construction du fichier d'import des Contrats de vente"

cat $DATA_DIR/contrats.csv | tr -d "\n" | tr "\r" "\n" | sort -t ";" -k 16,16 > $DATA_DIR/contrats.csv.sorted.produits

join -a 1 -t ";" -1 16 -2 1 $DATA_DIR/contrats.csv.sorted.produits $DATA_DIR/produits_conversion.csv > $DATA_DIR/contrats_produits.csv

cat $DATA_DIR/contrats_produits.csv | sort -t ";" -k 24,24 > $DATA_DIR/contrats_produits.sorted.cepages

join -a 1 -t ";" -1 24 -2 1 $DATA_DIR/contrats_produits.sorted.cepages $DATA_DIR/cepages.csv.sorted > $DATA_DIR/contrats_produits_cepages.csv

# /!\ Transformation arbitraire de la ligne ou l'année est 211 => 2011, 22012 => 2012, 201 => 2015, 20112 => 2012
cat $DATA_DIR/contrats_produits_cepages.csv | sed 's/;4;10222;10222;211;/;4;10222;10222;2011;/g' | sed 's/;3;10194;10194;22012;/;3;10194;10194;2012;/g' | sed 's/;2;10849;10849;201;12;91236;/;2;10849;10849;2015;12;91236;/g' | sed 's/;88;7922;7922;20112;/;88;7922;7922;2012;/g' > $DATA_DIR/contrats_produits_cepages.clean.csv

# Début génération des Id couchDB
#tail -n 1 $DATA_DIR/contrats_produits_cepages.clean.csv | tr ";" "\n" | awk -F ";" 'BEGIN { nb=0 } { nb = nb + 1; print nb ";" $0 }'

cat $DATA_DIR/contrats_produits_cepages.clean.csv | sed -r 's/([0-9]*);([0-9]*);([0-9]*);([0-9]*);([0-9]{2});(.*)/\1;\2;\3;\4;20\5;\6/g' | awk -F ';' '{ 
date_signature=gensub(/^([0-9]+)-([0-9]+)-([0-9]+)$/,"\\3-\\1-\\2", 1, $9); 
date_saisie=gensub(/^([0-9]+)-([0-9]+)-([0-9]+)$/,"\\3-\\1-\\2", 1, $11); 
num_bordereau=$7;
if(length($7) > 7){
   num_bordereau=substr($7,1,7);
}
id_vrac=sprintf("%4d%07d", $5 , num_bordereau);
produit_id=$2; 
libelle_produit=$41; 
vin_bio=$19;
vin_prepare=$20;
caracteristiques_vins="";
preparation_vin="";
if(vin_bio=="O"){
  caracteristiques_vins="agriculture_biologique";
}
if(vin_prepare=="P"){
  preparation_vin="NEGOCE_ACHEMINE";
}

date_debut_retiraison=$26;
date_fin_retiraison=$28;

clause_reserve_propriete=($31 == "O") ? "clause_reserve_propriete" : "";

acompte=$32;
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

delais_paiement_libelle=$33;

commentaires="";
if(exclure_v2 == "O") {
    commentaires = "Ce contrat est un ancien contrat exclut depuis la v2\n";
}

commentaires=commentaires "" $30;
exclure_v2=$39;
degre=$23;
recipient_contenance="";
prix_unitaire_hl=$24;
volume_propose=$22;
volume_enleve="";


proprietaire="";
annule=$36;
statut="NONSOLDE";
if(annule=="O") {
  statut="ANNULE";
}


clauses=clause_reserve_propriete "," preparation_vin;

print $4 ";" id_vrac ";" num_bordereau ";"  date_signature ";" date_saisie ";VIN_VRAC;" statut ";" $12 ";;;" $13 ";" $14 ";" proprietaire ";" produit_id ";" libelle_produit ";" $17 ";" $1 ";" $41 ";;;;;" degre ";" recipient_contenance ";"  volume_propose ";hl;" volume_propose ";" volume_enleve ";" prix_unitaire_hl ";" prix_unitaire_hl ";" cle_delais_paiement ";" delais_paiement_libelle ";" acompte ";;;;100_ACHETEUR;" date_debut_retiraison ";" date_fin_retiraison ";" clauses ";" caracteristiques_vins ";" commentaires
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

echo "Construction du fichier d'import des DRM"

cat $DATA_DIR/DRM.csv | tr -d "\r" | sort -t ";" -k 6,6 > $DATA_DIR/drm.csv.produits.sorted

join -a 1 -t ";" -1 6 -2 1  $DATA_DIR/drm.csv.produits.sorted $DATA_DIR/produits_conversion.csv | sort -t ";" -k 2,3 > $DATA_DIR/drm_produits.csv

cat $DATA_DIR/drm_produits.csv | awk -F ';' '{
identifiant=sprintf("%06d01", $4);
base="CAVE;" $5 ";" identifiant ";;" $37 ";;;;;;;" ; 
print base "stocks_debut;initial;" $10 ; 
print base "stocks_debut;dont_revendique;" $10 ; 
if($11 > 0) { print base "entrees;recolte;" $11 } #récolte
if($11 < 0) { print base "sorties;entree_recolte_negative;" $11*-1 ";;;;entrée négative de récolte" ; } #récolte
if($12 > 0) { print base "entrees;revendication;" $12 ; } #volume agréé
if($12 < 0) { print base "sorties;entree_negative;" $12*-1 ";;;;entrée négative de volume agrée" ; } #volume agréé
if($13 > 0) { print base "entrees;declassement;" $13 ; } #declassement
if($13 < 0) { print base "sorties;entree_negative;" $13*-1 ";;;;entrée négative de déclassement" ; } #declassement
if($14 > 0) { print base "sorties;destructionperte;" $14 ; } #perte
if($15 > 0) { print base "sorties;distillationusageindustriel;" $15 ; } #lie_et_mouts
if($16 > 0) { print base "sorties;distillationusageindustriel;" $16 ; } #usages_industriels
if($17 > 0) { print base "sorties;ventefrancebouteillecrd;" $17 ; } #collective_ou_individuelle
if($17 < 0) { print base "entrees;sortie_negative;" $17*-1 ";;;;sortie négative de collective_ou_individuelle" ; }
if($18 > 0) { print base "sorties;vracsanscontratsuspendu;" $18 ; } #dsa_dsac
if($18 < 0) { print base "entrees;sortie_negative;" $18*-1 ";;;;sortie négative de dsa dsac" ; } #dsa_dsac
if($19 > 0) { print base "sorties;vracsanscontratsuspendu;" $19 ; } #facture_etc
if($19 < 0) { print base "entrees;sortie_negative;" $19*-1 ";;;;sortie négative de facture etc" } #facture_etc
if($20 > 0) { print base "sorties;vracsanscontratsuspendu;" $20 ; } #france_sans_contrat
if($20 < 0) { print base "entrees;sortie_negative;" $20*-1 ";;;;sortie négative de france sans contrat" ; } #france_sans_contrat
# if($21 > 0) { print base "sorties;vrac;" $21 ; } #france_sous_contrat
if($21 < 0) { print base "entrees;sortie_negative;" $21*-1 ";;;;sortie négative de france sous contrat" ; }
if($22 > 0) { print base "sorties;export;" $22 ";Union Européenne" ; }  #expedition_ue
if($22 < 0) { print base "entrees;sortie_negative;" $22*-1 ";;;;sortie négative de expedition ue" ; }  #expedition_ue
if($23 > 0) { print base "sorties;export;" $23 ";Hors Union Européenne" ; } #expedition_hors_ue
if($23 < 0) { print base "entrees;sortie_negative;" $23*-1 ";;;;sortie négative de expedition hors ue" ; } #expedition_hors_ue
if($24 > 0) { print base "sorties;travailafacon;" $24 ; } #relogement
if($24 < 0) { print base "entrees;sortie_negative;" $24*-1 ";;;;sortie négative de relogement" ; } #relogement
print base "stocks_fin;final;" $25 ;
print base "stocks_fin;dont_revendique;" $25 ;
# print base "stocks?;dont_volume_bloque;" $26 ; #dont_volume_bloque
# print base "stocks?;quantite_gagees;" $27 ; #quantite_gagees
}' > $DATA_DIR/drm_cave.csv

cat $DATA_DIR/DRM_Factures.csv | tr -d "\r" | sort -t ";" -k 5,5 > $DATA_DIR/drm_factures.csv.produits.sorted

join -a 1 -t ";" -1 5 -2 1  $DATA_DIR/drm_factures.csv.produits.sorted $DATA_DIR/produits_conversion.csv > $DATA_DIR/drm_factures_produits.csv

cat $DATA_DIR/drm_factures_produits.csv | awk -F ';' '{
if (!$10 || $10 == "INCONNU") { next }
identifiant=sprintf("%06d01", $17);
base="CAVE;" $5 ";" identifiant ";;" $45 ";;;;;;;" ; 
numero_contrat=gensub(/-/, "00", 1, $10);
print base "sorties;vrac;" $21 ";;" numero_contrat ; 
}' > $DATA_DIR/drm_cave_contrats.csv

cat $DATA_DIR/drm_cave.csv $DATA_DIR/drm_cave_contrats.csv | sort -t ";" -k 2,3  | grep -E "^[A-Z]+;(2012(08|09|10|11|12)|2013[0-1]{1}[0-9]{1}|2014[0-1]{1}[0-9]{1}|2015[0-1]{1}[0-9]{1});" > $DATA_DIR/drm.csv

rm -rf $DATA_DIR/drms; mkdir $DATA_DIR/drms

awk -F ";" '{print >> ("'$DATA_DIR'/drms/" $3 "_" $2 ".csv")}' $DATA_DIR/drm.csv

# ls $DATA_DIR/drms | while read ligne
# do
#     cat $DATA_DIR/drms/$ligne | awk -F ';' '
#       BEGIN { somme_transfert_appellation=0; somme_declassement=0; somme_recolte=0 } 
#       {  
#           if($13 == "transfertsrecolte") {
#               if($12 == "entrees") { somme_transfert_appellation = somme_transfert_appellation + $14; } 
#               if($12 == "sorties") { somme_transfert_appellation = somme_transfert_appellation - $14; } 
#           }
          
#           if($13 == "declassement") {
#               if($12 == "entrees") { somme_declassement = somme_declassement + $14; } 
#               if($12 == "sorties") { somme_declassement = somme_declassement - $14; } 
#           }

#           if($13 == "recolte") {
#               if($12 == "entrees") { somme_recolte = somme_recolte + $14; } 
#               if($12 == "sorties") { somme_recolte = somme_recolte - $14; } 
#           }
          
#       } 
#       END {

#           if(!somme_declassement && !somme_recolte && somme_transfert_appellation) {
#               somme_transfert_appellation = 0;
#           }

#           if((somme_declassement + somme_transfert_appellation) != 0 && (somme_declassement + somme_transfert_appellation + somme_recolte) != 0 ) {  
#               print "'$DATA_DIR/drms/$ligne';transfert: " somme_transfert_appellation
#               print "'$DATA_DIR/drms/$ligne';declassement: " somme_declassement
#               print "'$DATA_DIR/drms/$ligne';recolte: " somme_recolte
#           }
#       }'
# done

echo "Import des contacts"

php symfony import:societe $DATA_DIR/societes.csv
php symfony import:etablissement $DATA_DIR/etablissements.csv

echo "Import des contrats"

php symfony import:vracs $DATA_DIR/vracs.csv --env="ivso"

echo "Import des DRM"

ls $DATA_DIR/drms | while read ligne
do
    PERIODE=$(echo $ligne | sed 's/.csv//' | cut -d "_" -f 2)
    IDENTIFIANT=$(echo $ligne | sed 's/.csv//' | cut -d "_" -f 1)
    php symfony drm:edi-import $DATA_DIR/drms/$ligne $PERIODE $IDENTIFIANT --facture=true
#    php symfony drm:edi-import $DATA_DIR/drms/$ligne $PERIODE $IDENTIFIANT --creation-depuis-precedente=true
done

echo "Contrôle de cohérence des DRM"

cat $DATA_DIR/drm.csv | cut -d ";" -f 3 | sort | uniq | while read ligne  
do
    php symfony drm:controle-coherence "$ligne"
done