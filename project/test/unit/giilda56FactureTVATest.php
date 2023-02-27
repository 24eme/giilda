<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);
$t = new lime_test(2);
$factureJSON = '
{
   "_id": "FACTURE-ID11111-2023021401",
   "type": "Facture",
   "identifiant": "ID11111",
   "code_comptable_client": "ID11111",
   "numero_facture": "2023021401",
   "numero_piece_comptable": "F2300001",
   "numero_piece_comptable_origine": null,
   "numero_adherent": "ID11111",
   "date_emission": "2023-02-14",
   "date_facturation": "2023-02-14",
   "date_paiement": null,
   "date_echeance": "2023-03-30",
   "reglement_paiement": null,
   "montant_paiement": null,
   "campagne": "2023",
   "numero_archive": "00001",
   "statut": null,
   "region": null,
   "versement_comptable": 0,
   "versement_comptable_paiement": 0,
   "versement_sepa": 0,
   "arguments": {
       "MOUVEMENTS_DRM": "MOUVEMENTS_DRM"
   },
   "emetteur": {
       "adresse": null,
       "code_postal": null,
       "ville": null,
       "service_facturation": null,
       "telephone": null,
       "email": null
   },
   "declarant": {
       "nom": null,
       "num_tva_intracomm": null,
       "adresse": null,
       "adresse_complementaire": null,
       "commune": null,
       "code_postal": null,
       "raison_sociale": null
   },
   "total_ht": null,
   "total_ttc": null,
   "total_taxe": null,
   "lignes": {
       "DRM-ID11111-2023-01": {
           "libelle": "DRM de janvier 2023",
           "montant_tva": 20.93,
           "montant_ht": 104.7,
           "origine_mouvements": {
               "DRM-ID11111-2023-01": [
                   "c76d4ec55211b5cdeb29b56e5ffbfb05",
                   "5748629e5198494d48cbb0ff64959a1e",
                   "e2a0ffac67dd278bc6c539f695da5374",
                   "112658ed3c4bc999176f3c12b6616b82"
               ]
           },
           "details": [
               {
                   "libelle": "AOP Tranquilles Appellation1 Blanc",
                   "quantite": 2.53,
                   "taux_tva": 0.2,
                   "prix_unitaire": 12,
                   "montant_tva": 6.07,
                   "montant_ht": 30.36,
                   "origine_type": null,
                   "code_compte": null
               },
               {
                   "libelle": "AOP Tranquilles Appellation2 Blanc",
                   "quantite": 0.01,
                   "taux_tva": 0.2,
                   "prix_unitaire": 7,
                   "montant_tva": 0.01,
                   "montant_ht": 0.07,
                   "origine_type": null,
                   "code_compte": null
               },
               {
                   "libelle": "AOP Tranquilles Appellation2 Rouge",
                   "quantite": 10.61,
                   "taux_tva": 0.2,
                   "prix_unitaire": 7,
                   "montant_tva": 14.85,
                   "montant_ht": 74.27,
                   "origine_type": null,
                   "code_compte": null
               }
           ]
       }
   },
   "echeances": [
   ],
   "origines": {
       "DRM-ID11111-2023-01": "DRM-ID11111-2023-01"
   },
   "templates": {
   },
   "paiements": [
       {
           "date": "2023-03-12",
           "montant": 125.64,
           "type_reglement": "PRELEVEMENT_AUTO",
           "commentaire": null,
           "versement_comptable": null,
           "execute": false
       }
   ]
}
';

$facture = new Facture();
$facture->loadFromCouchdb(json_decode($factureJSON));
$t->is($facture->getMontantTva(), 20.93, "TVA par ligne OK");
$facture->add('total_taxe_is_globalise', true);
$t->is($facture->getMontantTva(), 20.94, "TVA globalisée OK");
