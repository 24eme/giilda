<?php

/**
 * Model for DRMDeclaration
 *
 */
class DRMDeclaration extends BaseDRMDeclaration {

    public function getChildrenNode() {

        return $this->certifications;
    }

    public function getMouvements($isTeledeclaration = false) {
        $produits = $this->getProduitsDetails();
        $mouvements = array();
        foreach ($produits as $produit) {
            $mouvements = array_replace_recursive($mouvements, $produit->getMouvements());
        }
        
        return $mouvements;
    }

    public function cleanDetails() {
        $delete = false;
        foreach ($this->getProduitsDetails() as $detail) {
            if ($detail->isSupprimable()) {
                $detail->delete();
                $delete = true;
            }
        }

        if ($delete) {
            $this->cleanNoeuds();
        }
    }

    public function cleanNoeuds() {
        $this->_cleanNoeuds();
    }

    public function hasProduitDetailsWithStockNegatif() {
        foreach ($this->getProduitsDetails() as $prod) {
            if ($prod->hasProduitDetailsWithStockNegatif()) {
                return true;
            }
        }
        return false;
    }

    public function getProduitsDetailsSorted($teledeclarationMode = false) {
        $produits = array();

        foreach($this->certifications as $certification) {

            $produits = array_merge($produits, $certification->getProduitsDetailsSorted($teledeclarationMode));
        }

        return $produits;
    }
    
    public function getProduitsDetailsByCertifications($isTeledeclarationMode = false) {
        foreach ($this->certifications as $certification) {
                $produitsDetailsByCertifications[$certification->getHash()] = new stdClass();
                $produitsDetailsByCertifications[$certification->getHash()]->certification = $certification->getConfig();
                $produitsDetailsByCertifications[$certification->getHash()]->produits = $certification->getProduitsDetailsSorted($isTeledeclarationMode);
        }
        
        return $produitsDetailsByCertifications;
    }

}
