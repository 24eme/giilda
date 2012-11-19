<?php

/**
 * Model for Revendication
 *
 */
class Revendication extends BaseRevendication {

    private $csvSource = null;
    private $produits = null;
    private $produitsAlias = null;
    private $produitsCodeDouane = null;
    private $etablissements = null;

    public function __construct() {
        parent::__construct();
    }

    public function storeDatas() {
        $this->setCSV();
        $this->setProduits();
        $this->setProduitsCodeDouaneHashes();
        foreach ($this->getCSV() as $num_ligne => $row) {
            try {
                $bailleur = null;
                $etb = $this->matchEtablissement($row);
                $hashLibelle = $this->matchProduit($row);
                if ($this->rowHasMetayage($row)) {
                    $bailleur = $this->matchBailleur($row);
                }
                $this->detectDoublon($row, $etb);
                $revendicationEtb = $this->datas->_add($etb->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_ID]);
                $revendicationEtb->storeDeclarant($etb);
                $revendicationEtb->storeProduits($num_ligne+1, $row, $hashLibelle, $bailleur);
            } catch (RevendicationErrorException $erreur) {
                $erreurSortie = $this->erreurs->_add($erreur->getErrorType());
                $erreurSortie->storeErreur($num_ligne+1, $row, $erreur);
                continue;
            }
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

    public function setProduitsCodeDouaneHashes() {
        if (!$this->produitsCodeDouane)
            $this->produitsCodeDouane = ConfigurationClient::getCurrent()->declaration->getProduitsHashByCodeDouane('INTERPRO-inter-loire');
        return $this->produitsCodeDouane;
    }

    public function setEtablissements() {
        if (!$this->etablissements)
            $this->etablissements = EtablissementAllView::getInstance()->findByInterpro('INTERPRO-inter-loire');
        return $this->etablissements;
    }

    public function getProduits() {
        if (!$this->produits)
            $this->setProduits();
        return $this->produits;
    }

    public function getProduitsAlias() {
        if (!$this->produitsAlias)
            $this->setProduitsAlias();
        return $this->produitsAlias;
    }

    public function getProduitsCodeDouaneHashes() {
        if (!$this->produitsCodeDouane)
            $this->setProduitsCodeDouaneHashes();
        return $this->produitsCodeDouane;
    }

    public function getEtablissements() {
        if (!$this->etablissements)
            $this->setEtablissements();
        return $this->etablissements;
    }

    private function matchEtablissement($row) {
        $cvi = $row[RevendicationCsvFile::CSV_COL_CVI];
        $etb = EtablissementFindByCviView::getInstance()->findByCvi($cvi);
        if (count($etb) != 1) {
            throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS);
        }
        return $etb[0];
    }

    private function matchProduit($row) {
        
        $produitsCodeDouaneHashes = $this->getProduitsCodeDouaneHashes();
        $produits = $this->getProduits();
        if(array_key_exists($row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT], $this->getProduitsCodeDouaneHashes())){
            $hash = $produitsCodeDouaneHashes[$row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT]];
            return array($hash,$produits[substr($hash,1,  strlen($hash)-1)]);
        }

        $libelle_prod = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        foreach ($this->getProduitsAlias() as $hashKey => $produitAliases) {
            foreach ($produitAliases as $alias) {
                if (Search::matchTermLight($libelle_prod, $alias)) {
                    $hash = str_replace('-', '/', $hashKey);
                    return array($hash, $produits[$hashKey]);
                }
            }
        }

        throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS);
    }

    private function rowHasMetayage($row) {
        return $row[RevendicationCsvFile::CSV_COL_PROPRIO_METAYER] == "2";
    }

    private function matchBailleur($row) {
        $etablissements = $this->getEtablissements();
        foreach ($etablissements->rows as $etablissement) {
            if (Search::matchTermLight($row[RevendicationCsvFile::CSV_COL_BAILLEUR], $etablissement->key[EtablissementAllView::KEY_NOM])) {
                return $etablissement;
            }
        }
        throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_BAILLEUR_NOT_EXISTS);
    }

    private function detectDoublon($row, $etb) {
        $etbId = $etb->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_ID];
        $code_produit = $row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT];
        if ($this->datas->exist($etbId)
                && $this->datas->{$etbId}->produits->exist($code_produit)
                && $this->datas->{$etbId}->commune == $row[RevendicationCsvFile::CSV_COL_VILLE]) {
            foreach ($this->datas->{$etbId}->produits->{$code_produit}->volumes as $num => $volume) {
                if ($volume->volume == $row[RevendicationCsvFile::CSV_COL_VOLUME]) {
                    throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_DOUBLON, array('num_ligne' => $volume->num_ligne));
                }
            }
        }
    }

    public function updateProduit($cvi, $produit_key_old, $produit_key_new) {
        $produitsCd = $this->getProduitsCodeDouaneHashes();
        $hash = $produitsCd[$produit_key_new]; 
        $hash = substr($hash, 1, strlen($hash));
        $produits = $this->getProduits();
        $libelle = $produits[$hash];      
        $this->getDatas()->get($cvi)->updateProduits($produit_key_old, $produit_key_new, $libelle);
        }
        
    public function updateVolume($cvi, $produit_key, $row, $num_ligne, $new_volume) {
        
        $volume = $this->getDatas()->get($cvi)->produits->get($produit_key)->volumes->add($row);
        $volume->num_ligne = $num_ligne;
        $volume->volume = $new_volume;
    }

    public function getProduitNode($cvi, $row) {
        foreach ($this->getDatas()->get($cvi)->produits as $hash_key => $produit) {
            if ($produit->volumes->exist($row)) {
                return $produit;
            }
        }
        return null;
    }

    public function updateErrors() {
        $num_ligne = count($this->erreurs) - 1;
        while ($num_ligne >= 0) {

            $erreur = $this->erreurs[$num_ligne];
            $row = explode('#', $erreur->ligne);
            $etb = $this->matchEtablissement($row);
            $hashLibelle = $this->matchProduit($row);
            //$bailleur = ($row->value[RevendicationCsvFile::CSV_COL_PROPRIO_METAYER])? $this->matchBailleur($row) : null;

            if ((!is_null($etb)) && (!is_null($hashLibelle))) {
                if (!array_key_exists($etb->key[EtablissementFindByCviView::KEY_ETABLISSEMENT_CVI], $this->datas))
                    $revendicationEtb = $this->datas->_add($etb->key[EtablissementFindByCviView::KEY_ETABLISSEMENT_CVI]);
                $revendicationEtb->storeDeclarant($etb);
                $revendicationEtb->storeProduits($num_ligne, $row, $hashLibelle, null);
                unset($this->erreurs[$num_ligne]);
            }
            $num_ligne--;
        }
    }

    public function deleteRow($cvi, $row) {
        $this->getProduitNode($cvi, $row)->supprProduit();
    }

    public function getNbErreurs() {
        $nb_erreur = 0;
        foreach ($this->erreurs as $erreurType) {
            foreach ($erreurType as $erreurData) {
                $nb_erreur+=count($erreurData);
            }
        }
        return $nb_erreur;
    }

}