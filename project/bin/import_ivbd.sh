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

    cd IVBD

    rename 'y/A-Z/a-z/' *

    cd $SYMFODIR

    echo "Conversion des fichiers en utf8"
    
    rm -rf $DATA_DIR
    mkdir -p $DATA_DIR

    ls $TMP/data_ivbd_origin/IVBD | while read ligne  
    do
        echo $DATA_DIR/$ligne
        iconv -f utf-16 -t utf-8 $TMP/data_ivbd_origin/IVBD/$ligne | tr -d "\n" | tr "\r" "\n"  > $DATA_DIR/$ligne
    done

    cat $TMP/data_ivbd_origin/IVBD/base_ppm.csv | tr -d "\n" | tr "\r" "\n"  > $DATA_DIR/base_ppm.csv

    rm -rf $TMP/data_ivbd_origin
fi

echo "Import de la configuration"

curl -X DELETE "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/CONFIGURATION"?rev=$(curl -sX GET "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/CONFIGURATION" | grep -Eo '"_rev":"[a-z0-9-]+"' | sed 's/"//g' | sed 's/_rev://')
php symfony import:configuration CONFIGURATION data/import/configuration/ivbd
php symfony cc > /dev/null

echo "CODE_VIN;CODE_SYNDICAT_VIN;CODE_COMPTA_VIN;CODE_COMPTA_VIN_FAMILLE;LIBELLE_VIN;AOC;CODE_AOC;LIBELLE_AOC;COULEUR_VIN" >> $DATA_DIR/contrats_vin_correspondance.csv
echo "CODE_VINS_PRODUITS;CODE_SYNDICAT_VIN;CODE_COMPTA_VIN;CODE_COMPTA_VIN_FAMILLE;LIBELLE_VIN;AOC;CODE_AOC;LIBELLE_AOC;COULEUR_VIN" >> $DATA_DIR/contrats_vin_correspondance.csv
cat $DATA_DIR/contrats_vin_correspondance.csv | cut -d ";" -f 1,5 | sort -t ";" -k 1,1 | sed 's/;Rosette/;Rosette Blanc doux/' | sed 's/;Montravel sec$/;Montravel Blanc sec/' | sed 's/;Monbazillac Grain Noble$/;Monbazillac Sélection de Grains Nobles/' | sed 's/;Côtes de duras sec$/;Côtes de Duras Blanc sec/' | sed 's/;Côtes de duras$/;Côtes de Duras Rouge/' | sed 's/;Côtes de duras$/;Côtes de Duras Rouge/' | sed 's/;Côtes de bergerac blanc$/;Côtes de Bergerac Blanc demi sec/' | sed 's/;Côtes bgrc rouge$/;Côtes de Bergerac Rouge/' | sed 's/;Bergerac sec$/;Bergerac Blanc sec/' | sed 's/;Bergerac sec$/;Bergerac Blanc sec/' | sed 's/;Vin de table blanc/;Vin sans IG Blanc/' | sed 's/;Vin de table rouge/;Vin sans IG Rouge/' | sed 's/;Vin de table rosé/;Vin sans IG Rosé/' | sed 's/;Vin de pays/;IGP/' | sed 's/;Côtes de montravel/;Côtes de Montravel Blanc doux/' > $DATA_DIR/produits.csv

echo "Import des contacts"

cat $DATA_DIR/base_ppm.csv | awk -F ";" '
{
    nom=$10 " " $11 " " $12; statut=($18) ? "SUSPENDU" : "ACTIF";  print $2 ";VITICULTEUR;" nom ";;" statut ";;" $26 ";;;adresse;;;;code_postal;commune;cedex;pays;email;tel_bureau;tel_perso;mobile;fax;web;commentaire"
}' > $DATA_DIR/societes.csv

cat $DATA_DIR/base_ppm.csv | awk -F ";" '
{
    nom=$10 " " $11 " " $12; statut=($18) ? "SUSPENDU" : "ACTIF";  print $2  ";" $2 ";VITICULTEUR;" nom ";" statut ";HORS_REGION;cvi;no_accises;carte_pro;recette_locale:adresse;;;;code_postal;commune;cedex;pays;email;tel_bureau;tel_perso;mobile;fax;web;commentaire"
}' > $DATA_DIR/etablissements.csv

php symfony import:societe $DATA_DIR/societes.csv
php symfony import:etablissement $DATA_DIR/etablissements.csv

echo "Import des contrats"

cat $DATA_DIR/contrats_contrat.csv | grep -E "^[0-9]+;" | sort -t ";" -k 14,14 > $DATA_DIR/contrats_contrat.csv.sorted.produits

join -t ";" -a 1 -1 14 -2 1 $DATA_DIR/contrats_contrat.csv.sorted.produits $DATA_DIR/produits.csv | sort > $DATA_DIR/contrats_contrat_produit.csv

cat $DATA_DIR/contrats_contrat_produit.csv | awk -F ';' 'BEGIN { num_bordereau_incr=1 } {
    type_contrat=($24 == "True") ? "VIN_BOUTEILLE" : "VIN_VRAC";
    bordereau_origin=gensub(/ /, "", "g", $36);
    if(bordereau_origin) {
        numero_bordereau=gensub(/^.+-([0-9]+)-.+$/, "20\\1", "", bordereau_origin) "" ((type_contrat == "VIN_VRAC") ? "1" : "2") "" gensub(/^.+-.+-([0-9]+)$/, "0\\1", "", bordereau_origin);
    } else {
        numero_bordereau="1990" ((type_contrat == "VIN_VRAC") ? "1" : "2") "" sprintf("%06d", num_bordereau_incr);
        num_bordereau_incr=num_bordereau_incr+1;
    }
    produit=$70;
    cepage="";
    millesime=($15 && $15 > 0) ? $15 : "";
    degre=$52;
    volume_propose=$47;
    prix_unitaire=$17;
    delai_paiement="";
    date_debut_retiraison=$25;
    date_fin_retiraison=$54;
    vendeur_id=($7) ? $7 : "";
    intermediaire_id=($14) ? $14 : "";
    acheteur_id=($10) ? $10 : "";
    courtier_id=($12) ? $12 : "";

    print $2 ";" numero_bordereau ";" $3 ";" $4 ";" type_contrat ";" vendeur_id ";;" intermediaire_id ";" acheteur_id ";" courtier_id ";" $1 ";" produit ";" millesime ";" cepage ";" cepage ";GENERIQUE;;;;" degre ";" volume_propose ";hl;" volume_propose ";" volume_propose ";" prix_unitaire ";" prix_unitaire ";" delai_paiement ";;;;;" "50" ";" date_debut_retiraison ";" date_fin_retiraison ";;"
}' | sort -rt ";" -k 3,3 > $DATA_DIR/vracs.csv

php symfony import:vracs $DATA_DIR/vracs.csv

echo "Import des DRM"

sort -t ';' -k 2,2 $DATA_DIR/contrats_drm_parametre_ligne.csv > $DATA_DIR/contrats_drm_parametre_ligne.sorted.csv
sort -t ';' -k 3,3 $DATA_DIR/contrats_drm_volume.csv > $DATA_DIR/contrats_drm_volume.sorted.csv
join -t ';' -1 3 -2 2  $DATA_DIR/contrats_drm_volume.sorted.csv  $DATA_DIR/contrats_drm_parametre_ligne.sorted.csv  > $DATA_DIR/contrats_drm_volume_ligne.csv

sort -t ';' -k 3,3 $DATA_DIR/contrats_drm_volume_ligne.csv > $DATA_DIR/contrats_drm_volume_ligne.sorted.csv
join -t ';' -1 3 -2 1 $DATA_DIR/contrats_drm_volume_ligne.sorted.csv $DATA_DIR/produits.csv > $DATA_DIR/contrats_drm_volume_ligne_produits.csv

sort -k 1,1 -t ';' $DATA_DIR/contrats_drm.csv > $DATA_DIR/contrats_drm.sorted.csv
sort -k 3,3 -t ';' $DATA_DIR/contrats_drm_volume_ligne_produits.csv > $DATA_DIR/contrats_drm_volume_ligne_produits.sorted.csv
join -t ';' -1 1 -2 3 $DATA_DIR/contrats_drm.sorted.csv $DATA_DIR/contrats_drm_volume_ligne_produits.sorted.csv > $DATA_DIR/contrats_drm_drm_volume.csv

cat $DATA_DIR/contrats_drm_drm_volume.csv | awk -F ';' '{ 
    type="CAVE";
    mois=sprintf("%02d", $4);
    annee=$5;
    periode=annee mois;
    identifiant=sprintf("%06d01", $7);
    num_accises="";
    num_archive=sprintf("%05d",$1);
    produit_libelle=$46;
    catmouvement="";
    mouvement_extravitis=$36;
    mouvement=$36;
    corrective=$23;
    regularisatrice=$24;
    volume=gensub(",", ".", "", $33)+0;

    if(corrective == "True" || regularisatrice == "True") {

        next;
    }

    if(!mouvement_extravitis) {
        mouvement=$31;
    }
    if(mouvement_extravitis == "Solde précédent" && volume != 0) {
        catmouvement="stocks_debut"
        mouvement="revendique";
    }
    if(mouvement_extravitis == "Total DCA hors contrats(droits suspendus) - Autres") {
        catmouvement="sorties"
        mouvement="vracsanscontratsuspendu";
    }
    if(mouvement_extravitis == "Total DCA hors contrats(droits suspendus) -Export") {
        catmouvement="sorties"
        mouvement="export";
        next;
    }
    if(mouvement_extravitis == "Total CRD national") {
        catmouvement="sorties"
        mouvement="ventefrancebouteillecrd";
    }
    if(mouvement_extravitis == "Total DCA sous contrats (droits suspendus)") {
        catmouvement="sorties"
        mouvement="vrac";
        next;
    }

    if(mouvement_extravitis == "Entrées du mois suite à un repli") {
        catmouvement="entrees"
        mouvement="repli";
    }

    if(mouvement_extravitis == "Repli vers une autre AOC ou déclassement") {
        catmouvement="sorties"
        mouvement="repli";
    }

    if(mouvement_extravitis == "Entrées du mois (volumes revendiqués)") {
        catmouvement="entrees"
        mouvement="revendique";
    }

    if(mouvement_extravitis == "AOC sous réserve d'"'"'agrément") {
        catmouvement="entrees"
        mouvement="revendique";
    }

    if(mouvement_extravitis == "Autres exonérations") {
        catmouvement="sorties"
        mouvement="manquant";
    }

    if(mouvement_extravitis == "Autres entrées du mois" && mois != "08") {
        catmouvement="entrees"
        mouvement="regularisation";
    }

    if(mouvement_extravitis == "Autres entrées du mois" && mois == "08") {
        catmouvement="stocks_debut"
        mouvement="revendique";
    }

    if(mouvement_extravitis == "Total DSA, Fact.. (droits acquittés)") {
        catmouvement="sorties"
        mouvement="vracsanscontratacquitte";
    }

    if(!catmouvement) {
        next;
    }

    if(volume < 0 && catmouvement == "sorties") {
        catmouvement = "entrees";
        mouvement = "regularisation";
        volume = volume * -1;
    }

    if(volume < 0 && catmouvement == "entrees") {
        catmouvement = "sorties";
        mouvement = "manquant";
        volume = volume * -1;
    }

    print type ";" periode ";" identifiant ";" num_archive ";" produit_libelle ";;;;;;;" catmouvement ";" mouvement ";" volume ";;;";
}' > $DATA_DIR/drm_cave.csv

#Les contrats
sort -k 5,5 -t ';' $DATA_DIR/contrats_drm_dca.csv > $DATA_DIR/contrats_drm_dca.sorted.csv
join -t ';' -1 5 -2 1 $DATA_DIR/contrats_drm_dca.sorted.csv $DATA_DIR/produits.csv > $DATA_DIR/contrats_drm_dca_produit.csv 
sort -t ';' -k 4,4 $DATA_DIR/contrats_drm_dca_produit.csv  > $DATA_DIR/contrats_drm_dca_produit.sorted.csv 
join -t ';' -1 1 -2 4 $DATA_DIR/contrats_drm.sorted.csv $DATA_DIR/contrats_drm_dca_produit.sorted.csv > $DATA_DIR/contrats_drm_drm_dca.csv

cat $DATA_DIR/contrats_drm_drm_dca.csv | awk -F ';' '{ 
    type="CAVE";
    mois=sprintf("%02d", $4);
    annee=$5;
    periode=annee mois;
    identifiant=sprintf("%06d01", $7);
    num_accises="";
    num_archive=sprintf("%05d",$1);
    produit_libelle=$41;
    catmouvement="sorties";
    mouvement="vrac";
    corrective=$23;
    regularisatrice=$24;
    volume=gensub(",", ".", "", $36);
    num_contrat=$35

    if(corrective == "True" || regularisatrice == "True") {

        next;
    }

    print type ";" periode ";" identifiant ";" num_archive ";" produit_libelle ";;;;;;;" catmouvement ";" mouvement ";" volume ";;" num_contrat ";";
}' > $DATA_DIR/drm_cave_vrac.csv

#Les export
sort -k 3,3 -t ';' $DATA_DIR/contrats_drm_volume_export.csv > $DATA_DIR/contrats_drm_volume_export.sorted.csv
join -t ';' -1 3 -2 1 $DATA_DIR/contrats_drm_volume_export.sorted.csv $DATA_DIR/produits.csv > $DATA_DIR/contrats_drm_volume_export_produit.csv
sort -k 3,3 -t ';' $DATA_DIR/contrats_drm_volume_export_produit.csv > $DATA_DIR/contrats_drm_volume_export_produit.sorted.csv
join -t ';' -1 1 -2 3 $DATA_DIR/contrats_drm.sorted.csv $DATA_DIR/contrats_drm_volume_export_produit.sorted.csv > $DATA_DIR/contrats_drm_drm_export.csv

cat $DATA_DIR/contrats_drm_drm_export.csv | awk -F ';' '{ 
    type="CAVE";
    mois=sprintf("%02d", $4);
    annee=$5;
    periode=annee mois;
    identifiant=sprintf("%06d01", $7);
    num_accises="";
    num_archive=sprintf("%05d",$1);
    produit_libelle=$35;
    catmouvement="sorties";
    mouvement="export";
    corrective=$23;
    regularisatrice=$24;
    volume=gensub(",", ".", "", $34);
    pays=$33

    if(corrective == "True" || regularisatrice == "True") {

        next;
    }

    print type ";" periode ";" identifiant ";" num_archive ";" produit_libelle ";;;;;;;" catmouvement ";" mouvement ";" volume ";" pays ";;";
}' > $DATA_DIR/drm_cave_export.csv

#Génération finale
cat $DATA_DIR/drm_cave.csv $DATA_DIR/drm_cave_vrac.csv $DATA_DIR/drm_cave_export.csv | grep -v ";Bordeaux" | sort -t ";" -k 2,3 > $DATA_DIR/drm.csv

echo -n > $TMP/drm_lignes.csv

cat $DATA_DIR/drm.csv | while read ligne  
do
    if [ "$PERIODE" != "$(echo $ligne | cut -d ";" -f 2)" ] || [ "$IDENTIFIANT" != "$(echo $ligne | cut -d ";" -f 3)" ]
    then

        if [ $(cat $TMP/drm_lignes.csv | wc -l) -gt 0 ]
        then
            php symfony drm:edi-import $TMP/drm_lignes.csv $PERIODE $IDENTIFIANT $(echo $ligne | cut -d ";" -f 4) --trace
        fi

        echo -n > $TMP/drm_lignes.csv

    fi
    PERIODE=$(echo $ligne | cut -d ";" -f 2)
    IDENTIFIANT="$(echo $ligne | cut -d ";" -f 3)"
    echo $ligne >> $TMP/drm_lignes.csv
done

