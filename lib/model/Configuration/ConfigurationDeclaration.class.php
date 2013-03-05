<?php

/**
 * Model for ConfigurationDeclaration
 *
 */
class ConfigurationDeclaration extends BaseConfigurationDeclaration {

    const TYPE_NOEUD = 'declaration';

    public function getChildrenNode() {

        return $this->certifications;
    }

    public function getProduits($interpro, $departement = null) {
        $produits = ConfigurationProduitsView::getInstance()->findProduitsByInterpro($interpro)->rows;

        return $produits;
    }

    public function getProduitsHashByCodeProduit($interpro) {

        return ConfigurationProduitsView::getInstance()->formatProduitsHashByCodeProduit($this->getProduits($interpro));
    }

    public function formatProduits($interpro, $departement = null, $format = "%g% %a% %m% %l% %co% %ce% (%code_produit%)") {

        return ConfigurationProduitsView::getInstance()->formatProduits($this->getProduits($interpro), $format);
    }

    public function setDonneesCsv($datas) {
        
    }
    
    public function hasDroits() {

        return false;
    }

    public function getTypeNoeud() {

        return self::TYPE_NOEUD;
    }

    public function getDensite() {
        if (!$this->exist('densite') || !$this->_get('densite')) {

            return $this->getParentNode()->getDensite();
        }

        return $this->_get('densite');
    }

}