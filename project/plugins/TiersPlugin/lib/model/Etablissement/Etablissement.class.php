<?php
/**
 * Model for Etablissement
 *
 */

class Etablissement extends BaseEtablissement {

	public function constructId() {

		$this->set('_id', 'ETABLISSEMENT-'.$this->identifiant);
	}
}