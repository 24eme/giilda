<?php

/**
 * Model for ConfigurationCepage
 *
 */
class ConfigurationCepage extends BaseConfigurationCepage {

    const TYPE_NOEUD = 'cepage';

    public function getChildrenNode() {

        return null;
    }

    public function getAppellation() {

        return $this->getCouleur()->getLieu()->getAppellation();
    }

    public function getCertification() {

        return $this->getAppellation()->getCertification();
    }

    public function getGenre() {

        return $this->getAppellation()->getGenre();
    }

    public function getLieu() {

        return $this->getCouleur()->getLieu();
    }

    public function getMention() {

        return $this->getLieu()->getMention();
    }

    public function getCepage() {

        return $this;
    }

    public function getProduitsAll($interpro = null, $departement = null) {
        
        return array($this->getHash() => $this);
    }

    public function compressDroits() {
        $this->compressDroitsSelf();
    } 

    public function getCouleur() {
        return $this->getParentNode();
    }

    public function setDonneesCsv($datas) {
        parent::setDonneesCsv($datas);

        $this->getCouleur()->setDonneesCsv($datas);
        $this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_CEPAGE_LIBELLE]) ? $datas[ProduitCsvFile::CSV_PRODUIT_CEPAGE_LIBELLE] : null;
        $this->code = $this->formatCodeFromCsv($datas[ProduitCsvFile::CSV_PRODUIT_CEPAGE_CODE]);
        
        $this->cepages_autorises = ($datas[ProduitCsvFile::CSV_PRODUIT_CEPAGES_AUTORISES]) ? explode('|', $datas[ProduitCsvFile::CSV_PRODUIT_CEPAGES_AUTORISES]) : array();

        $this->setDroitDouaneCsv($datas, ProduitCsvFile::CSV_PRODUIT_CEPAGE_CODE_APPLICATIF_DROIT);
        $this->setDroitCvoCsv($datas, ProduitCsvFile::CSV_PRODUIT_CEPAGE_CODE_APPLICATIF_DROIT); 
    }
    
    public function isCepageAutorise($cepage) {
    	return in_array($cepage, $this->cepages_autorises->toArray());
    }

    public function getCorrespondanceHash() {

        return $this->getDocument()->getCorrespondanceHash($this->getHash());
    }

    public function getTypeNoeud() {
        
        return self::TYPE_NOEUD;
    }

    public function addInterpro($interpro) 
    {
        
        return $this->getParentNode()->addInterpro($interpro);
    }

    public function hasDroits() {
        return true;
    }

    public function hasCodes() {
        
        return true;
    }

}