#!/bin/bash

cd $(dirname $0)/..

DBNAME=$(cat project/config/databases.yml | grep -A 4 couchdb | grep dbname | sed 's/.*:  *//')
DBURL=$(cat project/config/databases.yml  | grep -A 4 couchdb | grep dsn | sed 's/.*:  *//')

if test "$1"; then
    echo "Pour supprimer la base, taper O comme Oui"
    read oui
    if test "$oui" = "O"; then
	curl -s -X DELETE $DBURL$DBNAME
    fi
fi

if ! curl -s $DBURL$DBNAME | grep true > /dev/null ; then
	curl -s -X PUT $DBURL$DBNAME
    rm -rf .views
    mkdir .views
fi

# Viticulteurss

curl -s -X PUT -d '{"_id": "COMPTE-752370010001", "type": "Compte","identifiant": "752370010001","civilite": null,"prenom": null,"nom": "M. Actualys Viti","nom_a_afficher": "M. Actualys Viti","fonction": null,"commentaire": null,"origines": ["SOCIETE-7523700100"],"id_societe": "SOCIETE-7523700100","adresse_societe": 1,"adresse": "Le Giron","adresse_complementaire": null,"code_postal": "75100","commune": "PARIS","compte_type": "SOCIETE","cedex": null,"pays": "FR","email": "contact@actualys.com","telephone_perso": "","telephone_bureau": "01.00.00.00.00","telephone_mobile": "01.00.00.00.00","fax": "","interpro": "INTERPRO-declaration","statut": "ACTIF","tags": {"automatique": ["societe"]},"droits": ["teledeclaration", "teledeclaration_drm"]}' $DBURL$DBNAME/COMPTE-752370010001

curl -s -X PUT -d '{ "_id": "ETABLISSEMENT-752370010001", "type": "Etablissement", "cooperative": null, "interpro": "INTERPRO-declaration", "identifiant": "752370010001", "id_societe": "SOCIETE-7523700100", "statut": "ACTIF", "raisins_mouts": null, "exclusion_drm": null, "relance_ds": null, "recette_locale": { "id_douane": null, "nom": null, "ville": null }, "region": "HORS_REGION", "type_dr": null, "liaisons_operateurs": { }, "site_fiche": null, "compte": "COMPTE-752370010001", "num_interne": null, "raison_sociale": "M. Actualys Jean", "nom": "M. Actualys Viti", "cvi": null, "no_accises": null, "carte_pro": null, "famille": "PRODUCTEUR", "sous_famille": "CAVE_PARTICULIERE", "email": null, "telephone": null, "fax": null, "commentaire": null, "siege": { "adresse": "Le Giron", "code_postal": "75100", "commune": "PARIS" }, "comptabilite": { "adresse": null, "code_postal": null, "commune": null }, "crd_regime": "COLLECTIFACQUITTE" }' $DBURL$DBNAME/ETABLISSEMENT-752370010001

curl -s -X PUT -d '{ "_id": "SOCIETE-7523700100", "type": "Societe", "identifiant": "7523700100", "type_societe": "VITICULTEUR", "raison_sociale": "M. Actualys Viti", "raison_sociale_abregee": "Actualys Viti", "statut": "ACTIF", "code_comptable_client": null, "code_comptable_fournisseur": null, "code_naf": null, "siret": null, "interpro": "INTERPRO-declaration", "no_tva_intracommunautaire": null, "email": "actualys@example.org", "telephone": "01.00.00.00.00", "fax": "", "commentaire": null, "siege": { "adresse": "rue Garnier", "code_postal": "75100", "commune": "PARIS", "pays": "FR" }, "cooperative": null, "enseignes": [ ], "compte_societe": "COMPTE-752370010001", "contacts": { "COMPTE-752370010001": { "nom": "M. Actualys Viti", "ordre": 0 } }, "etablissements": { "ETABLISSEMENT-752370010001": { "nom": "M. Actualys Viti", "ordre": null } }, "date_modification": "2015-02-20" }' $DBURL$DBNAME/SOCIETE-7523700100

#Configuration

curl -s -X PUT -d '{ "_id": "CURRENT", "type": "Current", "configurations": { "1994-08-01": "CONFIGURATION" } }' $DBURL$DBNAME/CURRENT

cd project;
php symfony import:configuration CONFIGURATION data/import/configuration/sancerre --application="bivc"
php symfony import:CVO CONFIGURATION data/import/configuration/sancerre/cvo.csv --application="bivc"
php symfony cc
cd ..

make
