#!/bin/bash

. bin/config.inc

REMOTE_DATA=$1

SYMFODIR=$(pwd);
DATA_DIR=$TMP/data_ivbd_csv

if test "$REMOTE_DATA"; then
    echo "Récupération de l'archive"
    scp $REMOTE_DATA $TMP/data_ivbd.tgz
    
    echo "Désarchivage"
    rm -rf $TMP/data_ivbd_origin
    mkdir $TMP/data_ivbd_origin
    cd $TMP/data_ivbd_origin
    tar -zxf $TMP/data_ivbd.tgz

    rm $TMP/data_ivbd.tgz

    cd IVBD

    rename 'y/A-Z/a-z/' *

    cd $SYMFODIR

    echo "Conversion des fichiers en utf8"
    
    rm -rf $DATA_DIR
    mkdir -p $DATA_DIR

    file -i $TMP/data_ivbd_origin/IVBD/* | grep "utf-16" | cut -d ":" -f 1 | sed -r 's|^.+/||' | while read ligne
    do
        #echo "$DATA_DIR/$ligne utf-16le" 
        iconv -f utf-16le -t utf-8 $TMP/data_ivbd_origin/IVBD/$ligne | tr -d "\n" | tr "\r" "\n"  > $DATA_DIR/$ligne
    done

    file -i $TMP/data_ivbd_origin/IVBD/* | grep -E "(iso-8859-1|unknown-8bit)" | cut -d ":" -f 1 | sed -r 's|^.+/||' | while read ligne
    do
        #echo "$DATA_DIR/$ligne iso-8859-1"
        iconv -f iso-8859-1 -t utf-8 $TMP/data_ivbd_origin/IVBD/$ligne | tr -d "\n" | tr "\r" "\n"  > $DATA_DIR/$ligne
    done

    file -i $TMP/data_ivbd_origin/IVBD/* | grep -Ev "(iso-8859-1|utf-16|unknown-8bit)" | cut -d ":" -f 1 | sed -r 's|^.+/||' | while read ligne
    do
        #echo "$DATA_DIR/$ligne autres"
        cat $TMP/data_ivbd_origin/IVBD/$ligne | tr -d "\n" | tr "\r" "\n"  > $DATA_DIR/$ligne
    done

    rm -rf $TMP/data_ivbd_origin
fi

echo "Import de la configuration"

curl -sX DELETE "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/CONFIGURATION"?rev=$(curl -sX GET "http://$COUCHHOST:$COUCHPORT/$COUCHBASE/CONFIGURATION" | grep -Eo '"_rev":"[a-z0-9-]+"' | sed 's/"//g' | sed 's/_rev://')
php symfony import:configuration CONFIGURATION data/import/configuration/ivbd
php symfony cc > /dev/null

echo "CODE_VIN;CODE_SYNDICAT_VIN;CODE_COMPTA_VIN;CODE_COMPTA_VIN_FAMILLE;LIBELLE_VIN;AOC;CODE_AOC;LIBELLE_AOC;COULEUR_VIN" >> $DATA_DIR/contrats_vin_correspondance.csv
echo "CODE_VINS_PRODUITS;CODE_SYNDICAT_VIN;CODE_COMPTA_VIN;CODE_COMPTA_VIN_FAMILLE;LIBELLE_VIN;AOC;CODE_AOC;LIBELLE_AOC;COULEUR_VIN" >> $DATA_DIR/contrats_vin_correspondance.csv
cat $DATA_DIR/contrats_vin_correspondance.csv | cut -d ";" -f 1,5 | sort -t ";" -k 1,1 | sed 's/;Rosette/;Rosette Blanc doux/' | sed 's/;Montravel sec$/;Montravel Blanc sec/' | sed 's/;Monbazillac Grain Noble$/;Monbazillac Sélection de Grains Nobles/' | sed 's/;Côtes de duras sec$/;Côtes de Duras Blanc sec/' | sed 's/;Côtes de duras$/;Côtes de Duras Rouge/' | sed 's/;Côtes de duras$/;Côtes de Duras Rouge/' | sed 's/;Côtes de bergerac blanc$/;Côtes de Bergerac Blanc demi sec/' | sed 's/;Côtes bgrc rouge$/;Côtes de Bergerac Rouge/' | sed 's/;Bergerac sec$/;Bergerac Blanc sec/' | sed 's/;Bergerac sec$/;Bergerac Blanc sec/' | sed 's/;Vin de table blanc/;Vin sans IG Blanc/' | sed 's/;Vin de table rouge/;Vin sans IG Rouge/' | sed 's/;Vin de table rosé/;Vin sans IG Rosé/' | sed 's/;Vin de pays/;IGP/' | sed 's/;Côtes de montravel/;Côtes de Montravel Blanc doux/' > $DATA_DIR/produits.csv

echo "Construction du fichier d'import des Contacts"

cat $DATA_DIR/base_ppm.csv | sort -t ";" -k 2,2 > $DATA_DIR/base_ppm.sorted.id.csv

cat $DATA_DIR/base_coordonnees.csv | sort -t ";" -k 3,3 > $DATA_DIR/base_coordonnees.sorted.id.csv

join -t ";" -1 2 -2 3 $DATA_DIR/base_ppm.sorted.id.csv $DATA_DIR/base_coordonnees.sorted.id.csv > $DATA_DIR/base_ppm_coordonnees.csv

cat $DATA_DIR/base_ppm_coordonnees.csv | sort -t ";" -k 41,41 | sed 's/;COMMUNE;/;INSEE;/' > $DATA_DIR/base_ppm_coordonnees.sorted.communes.csv

cat $DATA_DIR/base_commune_francaise.csv | awk -F ";" '{ insee=$10; commune=$17; prefix=gensub(/[()]+/, "", "g", $16); if (prefix && prefix != "L'"'"'") { prefix = prefix " "; } commune = prefix "" commune; print insee ";" commune }' | sed -r 's/[+]{1}(.{1})/\U\1/g' | sort -t ";" -k 1,1 > $DATA_DIR/communes.csv

join -t ";" -a 1 -1 41 -2 1 -o auto $DATA_DIR/base_ppm_coordonnees.sorted.communes.csv $DATA_DIR/communes.csv | sort > $DATA_DIR/base_ppm_coordonnees_communes.csv

cat $DATA_DIR/base_ppm_coordonnees_communes.csv | awk -F ";" '
{
    identifiant=sprintf("%06d", $2);
    nom=$11 " " $12 " " $13;
    statut=($19) ? "SUSPENDU" : "ACTIF";
    adresse1=$38;
    adresse2=$39;
    adresse3=$40;
    code_postal=$42;
    insee=$1;
    commune=$60;
    cedex=$44;
    siren=$26;
    siret=$27;
    if(!siret && siren) {
        siret=siren;
    }
    pays=$41;
    email="";
    tel_bureau="";
    tel_perso="";
    mobile="";
    fax="";
    web="";
    commentaire="";

    print identifiant ";VITICULTEUR;" nom ";;" statut ";;" siret ";;;" adresse1 ";" adresse2 ";" adresse3 ";;" code_postal ";" commune ";" cedex ";" pays ";" email ";" tel_bureau ";" tel_perso ";" mobile ";" fax ";" web ";" commentaire
}' | sort > $DATA_DIR/societes.csv

cat $DATA_DIR/base_ppm.csv | awk -F ";" '
{
    nom=$10 " " $11 " " $12; statut=($18) ? "SUSPENDU" : "ACTIF";  print $2  ";" $2 ";VITICULTEUR;" nom ";" statut ";HORS_REGION;cvi;no_accises;carte_pro;recette_locale:adresse;;;;code_postal;commune;cedex;pays;email;tel_bureau;tel_perso;mobile;fax;web;commentaire"
}' > $DATA_DIR/etablissements.csv

#cat $DATA_DIR/base_evv.csv | sort -t ";" -k 1,1

echo "Construction du fichier d'import des Contrats de vente"

# Supprimer les retours chariots au milieu d'une lignes
cat $DATA_DIR/contrats_contrat.csv | tr "\n" "#" | sed -r 's/;([,0-9-]*|VOLUME_SOLDAGE)#/;\1|/g' | tr -d "#" | tr "|" "\n" > $DATA_DIR/contrats_contrat.cleaned.csv

cat $DATA_DIR/contrats_contrat.cleaned.csv | sort -t ";" -k 14,14 | sed 's/;VIN;/;CODE_VIN;/' | sort -t ";" -k 14,14 > $DATA_DIR/contrats_contrat.csv.sorted.produits

join -t ";" -1 14 -2 1 $DATA_DIR/contrats_contrat.csv.sorted.produits $DATA_DIR/produits.csv > $DATA_DIR/contrats_contrat_produit.csv

sort -t ";" -k 58,58 $DATA_DIR/contrats_contrat_produit.csv > $DATA_DIR/contrats_contrat_produit.csv.delai_paiement
cat $DATA_DIR/contrats_p_paiement_delai.csv | sed 's/CLE/DELAI_PAIEMENT/' | sed 's/LIBELLE/DELAI_PAIEMENT_LIBELLE/' | cut -d ";" -f 2,3 | sort -t ";" -k 2,2 > $DATA_DIR/contrats_p_paiement_delai.csv.sorted

join -t ";" -a 1 -1 58 -2 2 -o auto $DATA_DIR/contrats_contrat_produit.csv.delai_paiement $DATA_DIR/contrats_p_paiement_delai.csv.sorted > $DATA_DIR/contrats_contrat_produit_delai_paiement.csv

sort -t ";" -k 1,1 $DATA_DIR/contrats_retiraison.csv > $DATA_DIR/contrats_retiraison.sorted.csv
sort -t ";" -k 3,3 $DATA_DIR/contrats_contrat_produit_delai_paiement.csv > $DATA_DIR/contrats_contrat_produit_delai_paiement.sorted.csv

join -t ";" -a 1 -1 3 -2 1 -o auto $DATA_DIR/contrats_contrat_produit_delai_paiement.sorted.csv $DATA_DIR/contrats_retiraison.sorted.csv > $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison.csv

#Type de vins
cat $DATA_DIR/contrats_p_type_vin.csv | sed 's/CLE/TYPE_VIN/' | sed 's/LIBELLE/TYPE_VIN_LIBELLE/' | cut -d ";" -f 2,3 | sort -t ";" -k 2,2 > $DATA_DIR/contrats_p_type_vin.sorted.csv
sort -t ";" -k 21,21 $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison.csv > $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison.sorted.csv

join -t ";" -1 21 -2 2 $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison.sorted.csv $DATA_DIR/contrats_p_type_vin.sorted.csv > $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin.csv

#Marque
cat $DATA_DIR/contrats_contrat_marques.csv | sed 's/ID_Marque/ID_MARQUE/' | sort -t ";" -k 2,2 > $DATA_DIR/contrats_contrat_marques.sorted.csv
cat $DATA_DIR/contrats_marque.csv | cut -d ";" -f 1,7 | sed 's/Id_Marque/ID_MARQUE/' | sort -t ";" -k 1,1 > $DATA_DIR/contrats_marque.sorted.csv

join -t ";" -1 2 -2 1 $DATA_DIR/contrats_contrat_marques.sorted.csv $DATA_DIR/contrats_marque.sorted.csv | cut -d ";" -f 2,3 | sort -t ";" -k 1,1 > $DATA_DIR/contrats_contrat_marques_libelle.sorted.csv
echo "NUM_CONTRAT;LIBELLE_MARQUE" >> $DATA_DIR/contrats_contrat_marques_libelle.sorted.csv

sort -t ";" -k 2,2 $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin.csv > $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin.sorted.csv

join -t ";" -a 1 -1 2 -2 1 -o auto $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin.sorted.csv $DATA_DIR/contrats_contrat_marques_libelle.sorted.csv | sed 's/;TYPE_VIN_LIBELLE;$/;TYPE_VIN_LIBELLE;LIBELLE_MARQUE/' > $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin_marque.csv

#tail -n 1 $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin_marque.csv | tr ";" "\n" | awk -F ";" 'BEGIN { nb=0 } { nb = nb + 1; print nb ";" $0 }'

cat $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin_marque.csv | sed 's/;I-06-04715BGR;/;I-06-04715;/' | sed 's/;I-04-?????;/;;/' | sed 's/;I-01331;/;I-03-01331;/' | sed 's/;I-00923;/;I-04-00923;/' | sed 's/;I-00965;/;I-05-00965;/' | sed -r 's/;I-04\+02230;/;I-04-02230;/' | sed 's/;I-0401906;/;I-04-01906;/' | sed 's/;I-0600085;/;I-06-00085;/' | sed 's/;B-04--00463;/;B-04-00463;/' | sed 's/;I-0600090;/;I-06-00090;/' | sed 's/;I-06-AAAAA;/;;/' | sed 's/;I-06-BBBBB;/;;/' | sed 's/;I--06-01761;/;I-06-01761;/' | sed 's/;I-06;/;;/' | sed 's/;I-04672;//' | sed 's/;I-04672;/;I-03-04672;/' | sed 's/;IV-1200847;/;IV-12-00847;/' | sed 's/;IV-12-\*01626;/;IV-12-01626;/' | sed 's/;IV-1201414;/;IV-12-01414;/' | sed 's/;I-0400625;/;I-04-00625;/' | sed 's/;I-04-003355;/;I-04-03355;/' | sed 's/;I-04-003355;/;I-04-03355;/' | sed 's/;I-04-019463;/;I-04-19463;/' | sed 's/;I-047-00456;/;I-04-00456;/' | sed 's/;IV-12-020411;/;IV-12-20411;/' | sed 's/;I-04-028183;/;I-04-28183;/' > $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin_marque.cleaned.csv

cat $DATA_DIR/contrats_contrat_produit_delai_paiement_retiraison_type_vin_marque.cleaned.csv | awk -F ';' 'BEGIN { num_bordereau_incr=1 } {
    type_contrat=($25 == "True") ? "VIN_BOUTEILLE" : "VIN_VRAC";
    bordereau_origin=gensub(/ /, "", "g", $37);
    if(bordereau_origin) {
        numero_bordereau=gensub(/^.+-([0-9]+)-.+$/, "20\\1", 1, bordereau_origin) "" ((type_contrat == "VIN_VRAC") ? "1" : "2") "" gensub(/^.+-.+-([0-9]+)$/, "0\\1", 1, bordereau_origin);
    } else {
        numero_bordereau="1990" ((type_contrat == "VIN_VRAC") ? "1" : "2") "" sprintf("%06d", num_bordereau_incr);
        num_bordereau_incr=num_bordereau_incr+1;
    }
    num=$1;
    date_signature=$5;
    date_saisie=$6;
    produit_id=$4;
    produit=$70;
    cepage="";
    millesime=($17 && $17 > 0) ? $17 : "";
    degre=$53;
    bouteille_contenance=($72) ? $72 / 10000 : "";
    volume_propose=$48;
    prix_unitaire=$19;
    acompte=$58;
    date_debut_retiraison=$26;
    date_fin_retiraison=$55;
    vendeur_id=($9) ? $9 : "";
    intermediaire_id=($16) ? $16 : "";
    acheteur_id=($12) ? $12 : "";
    courtier_id=($14) ? $14 : "";
    bio=($52) ? "agriculture_biologique" : ""; 
    categorie_vin=$78;
    if(categorie_vin == "AUTRES") {
        categorie_vin="GENERIQUE";
    }
    categorie_vin_info=$79;
    proprietaire=$65;
    if(proprietaire == "V") { proprietaire = "vendeur"; }
    if(proprietaire == "I") { proprietaire = "vendeur"; }
    if(proprietaire == "A") { proprietaire = "acheteur"; }
    if(proprietaire == "C") { proprietaire = "mandataire"; }
    statut="NONSOLDE";
    if($34=="True") { statut="ANNULE"; }

    cle_delais_paiement="";
    libelle_delais_paiement=$71;
    if(libelle_delais_paiement=="A réception / Comptant"){
      cle_delais_paiement="COMPTANT";
    }else if(libelle_delais_paiement=="30 jours"){
      cle_delais_paiement="30_JOURS";
    }else if(libelle_delais_paiement=="45 jours"){
      cle_delais_paiement="45_JOURS";
    }else if(libelle_delais_paiement=="60 jours"){
      cle_delais_paiement="60_JOURS";
    }else if(libelle_delais_paiement=="75 jours"){
      cle_delais_paiement="75_JOURS";
    }

    if(!libelle_delais_paiement) {
        libelle_delais_paiement="Autre / Non précisé";
    }

    clause_reserve_propriete=($54 == "True") ? "clause_reserve_propriete" : "";
    autorisation_nom_vin=($22 == "True") ? "autorisation_nom_vin" : "";
    autorisation_nom_producteur=($23 == "True") ? "autorisation_nom_producteur" : "";
    crd_negoce=($73 == "True") ? "NEGOCE_ACHEMINE" : "";
    tire_bouche=($74 == "True") ? "ACHAT_TIRE_BOUCHE" : "";
    preparation_vin=($75 == "True") ? "PREPARATION_VIN_VENDEUR" : "PREPARATION_VIN_ACHETEUR";
    embouteillage=($76 == "True") ? "EMBOUTEILLAGE_VENDEUR" : "EMBOUTEILLAGE_ACHETEUR";

    clauses=autorisation_nom_vin "," autorisation_nom_producteur "," clause_reserve_propriete "," crd_negoce "," tire_bouche "," preparation_vin "," embouteillage;

    print num ";" numero_bordereau ";" date_signature ";" date_saisie ";" type_contrat ";" statut ";" vendeur_id ";;" intermediaire_id ";" acheteur_id ";" courtier_id ";" proprietaire ";" produit_id ";" produit ";" millesime ";" cepage ";" cepage ";" categorie_vin ";" categorie_vin_info ";;;" degre ";" bouteille_contenance ";" volume_propose ";hl;" volume_propose ";" volume_propose ";" prix_unitaire ";" prix_unitaire ";" delai_paiement_cle ";" delai_paiement_libelle ";" acompte ";;;;" "50" ";" date_debut_retiraison ";" date_fin_retiraison ";" clauses ";" bio ";;"
}' | sort -rt ";" -k 3,3 > $DATA_DIR/vracs.csv

echo "Construction du fichier d'import des DRM"

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
    volume=gensub(",", ".", 1, $33);
    commentaire="";

    if(corrective == "True" || regularisatrice == "True") {

        next;
    }

    if(!mouvement_extravitis) {
        mouvement=$31;
    }
    
    if(mouvement_extravitis == "Solde précédent" && volume*1 != 0) {
        catmouvement="stocks_debut"
        mouvement="initial";
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
        mouvement="revendication";
    }

    if(mouvement_extravitis == "AOC sous réserve d'"'"'agrément") {
        catmouvement="entrees"
        mouvement="recolte";
    }

    if(mouvement_extravitis == "Autres exonérations") {
        catmouvement="sorties"
        mouvement="manquant";
        commentaire="Autres exonérations";
    }

    if(mouvement_extravitis == "Autres entrées du mois" && mois != "08") {
        catmouvement="entrees"
        mouvement="regularisation";
        commentaire="Autres entrées du mois";
    }

    if(mouvement_extravitis == "Autres entrées du mois" && mois == "08") {
        catmouvement="stocks_debut"
        mouvement="initial";
    }

    if(mouvement_extravitis == "Total DSA, Fact.. (droits acquittés)") {
        catmouvement="sorties"
        mouvement="vracsanscontratacquitte";
    }

    if(!catmouvement) {
        next;
    }

    if((volume * 1) < 0 && catmouvement == "sorties") {
        catmouvement = "entrees";
        mouvement = "sortie_negative";
        volume = volume * -1;
        commentaire="Sortie négative " mouvement_extravitis;
    }

    if((volume * 1) < 0 && catmouvement == "entrees") {
        catmouvement = "sorties";
        mouvement = "entree_negative";
        volume = volume * -1;
        commentaire="Entrée négative " mouvement_extravitis;
    }

    print type ";" periode ";" identifiant ";" num_archive ";" produit_libelle ";;;;;;;" catmouvement ";" mouvement ";" volume ";;;" commentaire;
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
    volume=gensub(",", ".", 1, $36);
    num_contrat=$35

    if(corrective == "True" || regularisatrice == "True") {

        next;
    }

    print type ";" periode ";" identifiant ";" num_archive ";" produit_libelle ";;;;;;;" catmouvement ";" mouvement ";" volume ";;" num_contrat ";";
}' > $DATA_DIR/drm_cave_vrac.csv

#Les export
sort -k 3,3 -t ';' $DATA_DIR/contrats_drm_volume_export.csv > $DATA_DIR/contrats_drm_volume_export.sorted.csv
join -t ';' -1 3 -2 1 $DATA_DIR/contrats_drm_volume_export.sorted.csv $DATA_DIR/produits.csv > $DATA_DIR/contrats_drm_volume_export_produit.csv

sort -k 5,5 -t ';' $DATA_DIR/contrats_drm_volume_export_produit.csv  > $DATA_DIR/contrats_drm_volume_export_produit.sorted.csv
sort -k 1,1 -t ";" $DATA_DIR/base_pays.csv | cut -d ";" -f 1,6 | sed 's/TCHEQUE (REPUBLIQUE)/République tchèque/' | sed 's/IRLANDE, ou EIRE/Irlande/' | sed 's/COREE (REPUBLIQUE DE)/Corée du Sud/' > $DATA_DIR/base_pays.sorted.csv

join -t ";" -1 5 -2 1 $DATA_DIR/contrats_drm_volume_export_produit.sorted.csv $DATA_DIR/base_pays.sorted.csv > $DATA_DIR/contrats_drm_volume_export_produit_pays.csv

sort -k 4,4 -t ';' $DATA_DIR/contrats_drm_volume_export_produit_pays.csv > $DATA_DIR/contrats_drm_volume_export_produit.sorted.csv
join -t ';' -1 1 -2 4 $DATA_DIR/contrats_drm.sorted.csv $DATA_DIR/contrats_drm_volume_export_produit.sorted.csv > $DATA_DIR/contrats_drm_drm_export.csv

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
    volume=gensub(",", ".", 1, $34);
    pays=$36

    if(corrective == "True" || regularisatrice == "True") {

        next;
    }

    print type ";" periode ";" identifiant ";" num_archive ";" produit_libelle ";;;;;;;" catmouvement ";" mouvement ";" volume ";" pays ";;";
}' > $DATA_DIR/drm_cave_export.csv

#Génération finale
cat $DATA_DIR/drm_cave.csv $DATA_DIR/drm_cave_vrac.csv $DATA_DIR/drm_cave_export.csv | grep -v ";Bordeaux" | sort -t ";" -k 2,3 | grep -E "^[A-Z]+;(2012(08|09|10|11|12)|2013[0-1]{1}[0-9]{1}|2014[0-1]{1}[0-9]{1}|2015[0-1]{1}[0-9]{1});" > $DATA_DIR/drm.csv


echo "Import des sociétés"

php symfony import:societe $DATA_DIR/societes.csv

echo "Import des établissements"

php symfony import:etablissement $DATA_DIR/etablissements.csv

echo "Import des contrats"

php symfony import:vracs $DATA_DIR/vracs.csv --env="ivbd"

echo "Import des DRM"

echo -n > $DATA_DIR/drm_lignes.csv

cat $DATA_DIR/drm.csv | while read ligne  
do
    if [ "$PERIODE" != "$(echo $ligne | cut -d ";" -f 2)" ] || [ "$IDENTIFIANT" != "$(echo $ligne | cut -d ";" -f 3)" ]
    then

        if [ $(cat $DATA_DIR/drm_lignes.csv | wc -l) -gt 0 ]
        then
            php symfony drm:edi-import $DATA_DIR/drm_lignes.csv $PERIODE $IDENTIFIANT $(echo $ligne | cut -d ";" -f 4) --trace
        fi

        echo -n > $DATA_DIR/drm_lignes.csv

    fi
    PERIODE=$(echo $ligne | cut -d ";" -f 2)
    IDENTIFIANT="$(echo $ligne | cut -d ";" -f 3)"
    echo $ligne >> $DATA_DIR/drm_lignes.csv
done

echo "Contrôle de cohérence des DRM"

cat $DATA_DIR/drm.csv | cut -d ";" -f 3 | sort | uniq | while read ligne  
do
  php symfony drm:controle-coherence "$ligne"
done