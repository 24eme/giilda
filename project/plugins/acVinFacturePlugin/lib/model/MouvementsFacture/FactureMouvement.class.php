<?php

/**
 * Model for FactureMouvement
 *
 */
class FactureMouvement extends BaseFactureMouvement {

    public function updateIdentifiantAnalytique($identifiant_analytique) {
        $comptabiliteDoc = ComptabiliteClient::getInstance()->findCompta();
        $node_analytique = $comptabiliteDoc->get('identifiants_analytiques')->get($identifiant_analytique);
        if (!$node_analytique) {
            throw new sfException("L'identifiant analytique $identifiant_analytique n'existe pas dans le document de COMPTABILITE");
        }
        $this->setIdentifiantAnalytique($identifiant_analytique);
        $this->setIdentifiantAnalytiqueLibelle($node_analytique->identifiant_analytique_libelle);
        $this->setIdentifiantAnalytiqueLibelleCompta($node_analytique->identifiant_analytique_libelle_compta);
    }

}
