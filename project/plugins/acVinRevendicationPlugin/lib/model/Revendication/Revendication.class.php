<?php

/**
 * Model for Revendication
 *
 */
class Revendication extends BaseRevendication {

    private $csvSource = null;
    private $produits = null;

    public function __construct() {
        parent::__construct();
    }

    public function storeDatas() {
        $this->setCSV();
        $this->setProduits();
        foreach ($this->getCSV() as $n => $row) {
            $etbId = $this->matchEtablissement($row);
            $hashLibelle = $this->matchProduit($row);
            $num_ligne = $n+1;
            if (is_null($etbId)) {
                $erreurSortie = $this->erreurs->_add($num_ligne);
                $erreurSortie->storeErreur($num_ligne, $row, RevendicationErreurs::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS);
                continue;
            }
            if (is_null($hashLibelle)) {
                $erreurSortie = $this->erreurs->_add($num_ligne);
                $erreurSortie->storeErreur($num_ligne, $row, RevendicationErreurs::ERREUR_TYPE_PRODUIT_NOT_EXISTS);
                continue;
            }
            $revendicationEtb = $this->datas->_add($etbId);
            $revendicationEtb->storeProduits($num_ligne, $row, $hashLibelle);
        }
    }

    public function setCSV() {
        $attachementFile = $this->getAttachmentUri('revendication.csv');
        $csv = new CsvFile($attachementFile);
        $this->csvSource = $csv->getCsv();
    }

    public function getCSV() {
        if (!$this->csvSource)
            $this->setCSV();
        return $this->csvSource;
    }

    public function setProduits() {
        if (!$this->produits)
            $this->produits = ConfigurationClient::getCurrent()->formatProduits();
        return $this->produits;
    }

    public function getProduits() {
        if (!$this->produits)
            $this->setProduits();
        return $this->produits;
    }

    private function matchEtablissement($row) {
        $cvi = $row[RevendicationCsvFile::CSV_COL_CVI];
        $etb = EtablissementFindByCviView::getInstance()->findByCvi($cvi);
        if (count($etb) != 1)
            return null;
        return $etb[0]->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_ID];
    }

    private function matchProduit($row) {
        $libelle_prod = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        foreach ($this->getProduits() as $hash => $produit) {
            if (Search::matchTermLight($libelle_prod, $produit))
                return array($hash, $produit);
        }
        return null;
    }

    public function sortByType() {
        $sortedErrors = new stdClass();
        $sortedErrors->erreurs = array();
        foreach ($this->erreurs as $erreur) {
            if (!array_key_exists($erreur->type_erreur, $sortedErrors->erreurs)) {
                $sortedErrors->erreurs[$erreur->type_erreur] = array();
            }
            if (!array_key_exists($erreur->data_erreur, $sortedErrors->erreurs[$erreur->type_erreur])) {
                $sortedErrors->erreurs[$erreur->type_erreur][$erreur->data_erreur] = array();
            }
            if(!isset($sortedErrors->{$erreur->type_erreur})) $sortedErrors->{$erreur->type_erreur}=0;
            
            $sortedErrors->{$erreur->type_erreur}++;
            $sortedErrors->erreurs[$erreur->type_erreur][$erreur->data_erreur][] = $erreur->num_ligne;
        }
        return $sortedErrors;
    }

}