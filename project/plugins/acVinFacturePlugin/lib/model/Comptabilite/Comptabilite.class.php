<?php
/**
 * Model for Comptabilite
 *
 */

class Comptabilite extends BaseComptabilite {


    public function getAllIdentifiantsAnalytiquesArrayForCompta() {
        $identifiant_analytique= array();
        $results = array();
        foreach ($this->identifiants_analytiques as $key => $identifiant_analytique) {
            $results[$identifiant_analytique->identifiant_analytique_numero_compte.'_'.$identifiant_analytique->identifiant_analytique] = $identifiant_analytique->identifiant_analytique_libelle_compta;
        }
        return $results;
    }
}
