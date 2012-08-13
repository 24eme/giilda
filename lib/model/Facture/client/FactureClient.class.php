<?php

class FactureClient extends acCouchdbClient {

    const FACTURE_LIGNE_ORIGINE_TYPE_DRM = "DRM";
    const FACTURE_LIGNE_ORIGINE_TYPE_SV = "SV";

    const FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE = "Propriete";
    const FACTURE_LIGNE_MOUVEMENT_TYPE_CONTRAT = "Contrat";

    const FACTURE_LIGNE_PRODUIT_TYPE_VINS = "Vins";
    const FACTURE_LIGNE_PRODUIT_TYPE_MOUTS = "Mouts";
    const FACTURE_LIGNE_PRODUIT_TYPE_RAISINS = "Raisins";

    public static function getInstance()
    {
      return acCouchdbManager::getClient("Facture");
    }  

    public function findByEtablissement($etablissement) {
      return acCouchdbManager::getClient()
	->startkey(array($etablissement->_id))
	->endkey(array($etablissement->_id, array()))
	->reduce(false)
	->getView("facture", "etablissement")
	->rows;

    }
}
