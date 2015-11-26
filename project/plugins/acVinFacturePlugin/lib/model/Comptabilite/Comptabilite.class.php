<?php
/**
 * Model for Comptabilite
 *
 */

class Comptabilite extends BaseComptabilite {

    
    public function getAllIdentifiantsAnalytiquesArrayForCompta() {
        $identifiant_analytique= array();
        foreach ($this->identifiants_analytiques as $key => $identifiant_analytique) {
            $results[$key] = $identifiant_analytique->identifiant_analytique_libelle_compta;
        } 
        return $results;
    }
}