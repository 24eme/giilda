<?php
/**
 * Model for Etablissement
 *
 */

class Etablissement extends BaseEtablissement {

	public function constructId() {

		$this->set('_id', 'ETABLISSEMENT-'.$this->identifiant);
	}
        
    public function getFamilleType() 
    {
        $familleType = array('Negociant' => 'acheteur',
                             'Viticulteur' => 'vendeur',
                             'Courtier' => 'mandataire');
        return $familleType[$this->famille];
    }

    public function getInterproObject() {

    	return new Interpro();
    }
}