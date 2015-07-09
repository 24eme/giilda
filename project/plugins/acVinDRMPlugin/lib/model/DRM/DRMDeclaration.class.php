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
    
    public function getProduitsDetailsByCertifications($isTeledeclarationMode = false) {
        $certifications = $this->getConfig()->getChildrenNode(); 
        $produitsDetailsByCertifications = array();
        foreach ($certifications as $certification) {
            if(!array_key_exists($certification->getHash(), $produitsDetailsByCertifications)){
                $produitsDetailsByCertifications[$certification->getHash()] = new stdClass();
                $produitsDetailsByCertifications[$certification->getHash()]->certification = $certification;
                $produitsDetailsByCertifications[$certification->getHash()]->produits = array();
            }
        }
        $produitsDetails = $this->getProduitsDetails($isTeledeclarationMode);
        foreach ($produitsDetails as $produitDetails) {
            $produitLibelle = str_replace(' ','',trim($produitDetails->getCepage()->getConfig()->formatProduitLibelle()));
            $produitsDetailsByCertifications[$produitDetails->getCertification()->getHash()]->produits[$produitLibelle] = $produitDetails;
        }
        foreach ($certifications as $certification) {
            ksort($produitsDetailsByCertifications[$certification->getHash()]->produits);
        }
        return $produitsDetailsByCertifications;
    }

}
