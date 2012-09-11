<?php
/**
 * Model for DRMDeclaration
 *
 */

class DRMDeclaration extends BaseDRMDeclaration {

	public function getChildrenNode() {

		return $this->certifications;
	}

    public function getMouvements() {
        $produits = $this->getProduits();
        $mouvements = array();
        foreach($produits as $produit) {
            $mouvements = array_replace_recursive($mouvements, $produit->getMouvements());
        }

        return $mouvements;
    }

}