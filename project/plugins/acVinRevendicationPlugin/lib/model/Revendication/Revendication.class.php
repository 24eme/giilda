<?php

/**
 * Model for Revendication
 *
 */
class Revendication extends BaseRevendication {

    private $csvSource = null;
    private $produits = null;
    private $produitsAlias = null;
    private $etablissements = null;

    public function __construct() {
        parent::__construct();
    }

    public function storeDatas() {
        $this->setCSV();
        $this->setProduits();
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
                $revendicationEtb->storeProduits($num_ligne, $row, $hashLibelle, $bailleur);
            } catch (RevendicationErrorException $erreur) {
                $erreurSortie = $this->erreurs->_add($num_ligne);
                $erreurSortie->storeErreur($num_ligne, $row, $erreur->getErrorType());
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
        $libelle_prod = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        $produits = $this->getProduits();
        foreach ($this->getProduitsAlias() as $hashKey => $produitAliases) {
            foreach ($produitAliases as $alias) {
                if (Search::matchTermLight($libelle_prod, $alias)) {
                    $hash = str_replace('-', '/', $hashKey);
                    return array($hash, $produits[$hash]);
                }
            }
        }

        foreach ($produits as $hash => $produit) {
            if (Search::matchTermLight($libelle_prod, $produit))
                return array($hash, $produit);
        }
        throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS);
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
                    throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_DOUBLON);
                }
            }
        }
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
        $this->getDatas()->get($cvi)->updateProduits($produit_hash_old, $produit_hash_new, $libelle);
    }

    public function updateVolume($cvi, $produit_hash, $row, $num_ligne, $new_volume) {
        $produit_hash_key = str_replace('/', '-', $produit_hash);
        $volume = $this->getDatas()->get($cvi)->produits->get($produit_hash_key)->volumes->add($row);
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

}