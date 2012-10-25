<?php

/**
 * Model for Revendication
 *
 */
class Revendication extends BaseRevendication {

    private $csvSource = null;
    private $produits = null;
    private $produitsAlias = null;
    
    public function __construct() {
        parent::__construct();
    }

    public function storeDatas() {
        $this->setCSV();
        $this->setProduits();
        $this->nb_data = 0;
        foreach ($this->getCSV() as $n => $row) {
            $etb = $this->matchEtablissement($row);
            $hashLibelle = $this->matchProduit($row);
            $num_ligne = $n + 1;
            if (is_null($etb)) {
                $erreurSortie = $this->erreurs->_add($num_ligne);
                $erreurSortie->storeErreur($num_ligne, $row, RevendicationErreurs::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS);
                continue;
            }
            if (is_null($hashLibelle)) {
                $erreurSortie = $this->erreurs->_add($num_ligne);
                $erreurSortie->storeErreur($num_ligne, $row, RevendicationErreurs::ERREUR_TYPE_PRODUIT_NOT_EXISTS);
                continue;
            }
            $revendicationEtb = $this->datas->_add($etb->key[EtablissementFindByCviView::KEY_ETABLISSEMENT_CVI]);
            $revendicationEtb->storeDeclarant($etb);
            $revendicationEtb->storeProduits($num_ligne, $row, $hashLibelle);
            $this->nb_data++;
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

    public function setProduitsAlias() {
        if (!$this->produitsAlias)
            $this->produitsAlias = ConfigurationClient::getCurrent()->getAlias();
        return $this->produitsAlias;
    }
    
    public function getProduits() {
        if (!$this->produits)
            $this->setProduits();
        return $this->produits;
    }
    
    public function getProduitsAlias(){
        if (!$this->produitsAlias)
            $this->setProduitsAlias();
        return $this->produitsAlias;
    }

    private function matchEtablissement($row) {
        $cvi = $row[RevendicationCsvFile::CSV_COL_CVI];
        $etb = EtablissementFindByCviView::getInstance()->findByCvi($cvi);
        if (count($etb) != 1)
            return null;
        return $etb[0];
    }

    private function matchProduit($row) {
        $libelle_prod = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        foreach ($this->getProduitsAlias() as $hash => $produitAliases)
        {
            foreach ($produitAliases as $alias) {
                if (Search::matchTermLight($libelle_prod, $alias))
                        return array(str_replace ('-', '/', $hash), $alias);
            }
        }
        
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
                $sortedErrors->erreurs[$erreur->type_erreur][$erreur->data_erreur] = new stdClass();
                $sortedErrors->erreurs[$erreur->type_erreur][$erreur->data_erreur]->lignes = array();
                $sortedErrors->erreurs[$erreur->type_erreur][$erreur->data_erreur]->libelle_erreur = $erreur->libelle_erreur;
            }
            if (!isset($sortedErrors->{$erreur->type_erreur}))
                $sortedErrors->{$erreur->type_erreur} = 0;

            $sortedErrors->{$erreur->type_erreur}++;
            $sortedErrors->erreurs[$erreur->type_erreur][$erreur->data_erreur]->lignes[] = $erreur->num_ligne;
        }
        return $sortedErrors;
    }

    public function updateProduit($cvi, $produit_hash_old, $produit_hash_new) {
        $produits = $this->getProduits();
        $libelle = $produits[$produit_hash_new]; 
        $this->getDatas()->get($cvi)->updateProduits($produit_hash_old,$produit_hash_new,$libelle);
    }

    public function updateVolume($cvi, $produit_hash, $row, $num_ligne, $new_volume) {
        $produit_hash_key = str_replace('/', '-', $produit_hash);
        $volume = $this->getDatas()->get($cvi)->produits->get($produit_hash_key)->volumes->add($row);
        $volume->num_ligne = $num_ligne;
        $volume->volume = $new_volume;
    }
    
    public function getProduitNode($cvi, $row)
    {
        foreach ($this->getDatas()->get($cvi)->produits as $hash_key => $produit) {
            if($produit->volumes->exist($row))
                {
                return $produit;
                }
        }
        return null;
    }
    
    public function updateErrors() {
        foreach ($this->erreurs as $num_ligne => $erreur) {
            $row = explode('#', $erreur->ligne);            
            $etb = $this->matchEtablissement($row);
            $hashLibelle = $this->matchProduit($row);
            
            if ((!is_null($etb)) && (!is_null($hashLibelle))) {
                if(!array_key_exists($etb->key[EtablissementFindByCviView::KEY_ETABLISSEMENT_CVI],  $this->datas))
                    $revendicationEtb = $this->datas->_add($etb->key[EtablissementFindByCviView::KEY_ETABLISSEMENT_CVI]);
                $revendicationEtb->storeDeclarant($etb);
                $revendicationEtb->storeProduits($num_ligne, $row, $hashLibelle);
                $this->erreurs->remove($num_lignes);
            }
        }
    }

}