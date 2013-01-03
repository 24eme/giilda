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

    public function updateCSV($path) {
        $this->storeAttachment($path, 'text/csv', 'revendication.csv');
    }

    public function storeDatas() {
        $this->setCSV();
        $this->setProduits();
        $this->setProduitsCodeDouaneHashes();

        $this->remove('erreurs');
	$this->add('erreurs');

        foreach ($this->getCSV() as $num_ligne => $row) {
            $this->insertRow($num_ligne, $row);
        }
    }

    public function addIgnoredLine($num_ligne, $num_ca) {
      return $this->ignored_lines->add($num_ligne.'_'.$num_ca, array('num_ligne' => $num_ligne, 'numero_certification' => $num_ca));
    }

    public function lineToBeIgnored($num_ligne, $row) {
      if ($this->ignored_lines->exist($num_ligne.'_'.$row[RevendicationCsvFile::CSV_COL_NUMERO_CA])) {
	return true;
      }
      if ($row[RevendicationCsvFile::CSV_COL_VOLUME]*1 < 0) {
	return true;
      }
    }

    public function insertRow($num_ligne, $row) {
      if ($this->lineToBeIgnored($num_ligne, $row)) {
	return ;
      }
        try {
            $bailleur = null;
            $etb = $this->matchEtablissement($row);
            $hashLibelle = $this->matchProduit($row);
            if ($this->rowHasMetayage($row)) {
                $bailleur = $this->matchBailleur($row,  EtablissementClient::getInstance()->find($etb->id));
            }
            $this->detectDoublon($row, $etb);
            $revendicationEtb = $this->datas->add($etb->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_ID]);
            $revendicationEtb->storeDeclarant($etb);
            $revendicationEtb->storeProduits($num_ligne, $row, $hashLibelle, $bailleur);
            return true;
        } catch (RevendicationErrorException $erreur) {
            $erreurSortie = $this->erreurs->add($erreur->getErrorType());
            return $erreurSortie->storeErreur($num_ligne, $row, $erreur);
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

    private function setEtablissements() {
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

        if (array_key_exists($row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT], $produitsCodeDouaneHashes)) {
            $hash = $produitsCodeDouaneHashes[$row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT]];

            return array($hash, $produits[$hash]);
        }

        $libelle_prod = $row[RevendicationCsvFile::CSV_COL_LIBELLE_PRODUIT];
        foreach ($this->getProduitsAlias() as $hashKey => $produitAliases) {
            foreach ($produitAliases as $alias) {
                if (Search::matchTermLight($libelle_prod, $alias)) {
                    $hash = str_replace('-', '/', $hashKey);
                    return array($hash, $produits[$hash]);
                }
            }
        }

        throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS);
    }

    private function rowHasMetayage($row) {
        return $row[RevendicationCsvFile::CSV_COL_PROPRIO_METAYER] == "2";
    }

    private function matchBailleur($row, $etb) {
        if(!count($etb->getBailleurs()))
            throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_NO_BAILLEURS,array('identifiant' => $etb->identifiant));        
        $nom = $row[RevendicationCsvFile::CSV_COL_BAILLEUR];        
        $bailleur = $etb->findBailleurByNom($nom);
        if(!$bailleur){
            throw new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_BAILLEUR_NOT_EXISTS,array('identifiant' => $etb->identifiant));
        }
        return $bailleur;
    }

    private function detectDoublon($row, $etb) {
        $etbId = $etb->value[EtablissementFindByCviView::VALUE_ETABLISSEMENT_ID];
        $code_produit = $row[RevendicationCsvFile::CSV_COL_CODE_PRODUIT];
	$md5 = md5($etbId.$code_produit.$row[RevendicationCsvFile::CSV_COL_VILLE].$row[RevendicationCsvFile::CSV_COL_DATE].$row[RevendicationCsvFile::CSV_COL_VOLUME]);
	$exception = new RevendicationErrorException(RevendicationErrorException::ERREUR_TYPE_DOUBLON, array('md5' => $md5, 'etablissement_id' => $etbId, 'code_produit' => $code_produit, 'num_ca' => $row[RevendicationCsvFile::CSV_COL_NUMERO_CA]));
	if ($this->erreurs->exist(RevendicationErrorException::ERREUR_TYPE_DOUBLON)) {
	    if ($this->erreurs[RevendicationErrorException::ERREUR_TYPE_DOUBLON]->exist($md5)) {
	      $exception->addArgument('exists', 'VRAI');
	      throw $exception;
	    }
	}
        // Les doublons ne comprennent pas la ligne doublonnée de base (qui fait partie des datas)! 
        if ($this->datas->exist($etbId)
                && $this->datas->{$etbId}->produits->exist($code_produit)
                && $this->datas->{$etbId}->commune == $row[RevendicationCsvFile::CSV_COL_VILLE]) {
	  foreach ($this->datas->{$etbId}->produits->{$code_produit}->volumes as $num_ca => $volume) {
	    if ($row[RevendicationCsvFile::CSV_COL_NUMERO_CA] != $num_ca && $volume->volume == $row[RevendicationCsvFile::CSV_COL_VOLUME] && $volume->date_certification == $row[RevendicationCsvFile::CSV_COL_DATE]) {
	      $exception->addArgument('inserted_volume', $volume);
	      $this->datas->{$etbId}->produits->{$code_produit}->volumes->remove($num_ca);
	      throw $exception;
	    }
	  }
        }
    }

//    public function updateVolumeProduit($cvi, $produit_key_old, $produit_key_new, $row, $num_ligne, $new_volume) {
//        var_dump('modif'); exit;
//        $produitsCd = $this->getProduitsCodeDouaneHashes();
//        $hash = $produitsCd[$produit_key_new]; 
//        $produits = $this->getProduits();
//        $libelle = $produits[$hash];         
//        $this->getDatas()->get($cvi)->updateProduitsAndVolume($this->getDatas()->get($cvi)->produits, $produit_key_old, $produit_key_new, $libelle,$row, $num_ligne, $new_volume);
//        }
//    public function majVolume($cvi, $produit_key, $row, $num_ligne, $new_volume) {
//        
//        $volume = $this->getDatas()->get($cvi)->produits->get($produit_key)->volumes->add($row);
//        $volume->num_ligne = $num_ligne;
//        $volume->volume = $new_volume;
//    }

    public function getProduitNode($cvi, $row) {
        foreach ($this->getDatas()->get($cvi)->produits as $hash_key => $produit) {
            if ($produit->volumes->exist($row)) {
                return $produit;
            }
        }
        return null;
    }

    public function updateErrors($type_error = null, $entity_error = null) {

        if ($type_error == RevendicationErrorException::ERREUR_TYPE_PRODUIT_NOT_EXISTS) {
            if (!($this->erreurs->exist($type_error) && $this->erreurs->$type_error->exist($entity_error)))
                throw new sfException("Il n'existe aucune erreur de type $type_error pour l'entité $entity_error");
            $errorsToCheck = $this->erreurs->$type_error->$entity_error;

            $key = count($errorsToCheck) - 1;
            while ($key >= 0) {
                $error = $errorsToCheck[$key];
                $row = explode('#', $error->ligne);
                $ret = $this->insertRow($error->num_ligne, $row);
                if ($ret == true) {
                    $error->delete();
                }
                $key--;
            }
            if(!count($errorsToCheck)) $errorsToCheck->delete();
            if(!count($this->erreurs->$type_error)) $this->erreurs->$type_error->delete();
        }
    }
    
    public function deteteDoublon($num_ligne,$doublon){
        $doublon_type = RevendicationErrorException::ERREUR_TYPE_DOUBLON;
        $errorsDoublons = $this->erreurs->$doublon_type->$num_ligne;
        $key = count($errorsDoublons) - 1;
        while ($key >= 0) {
            $error = $errorsDoublons[$key];
            if($error->num_ligne == intval($doublon)){
                $error->delete();
            }
            $key--;
        }
        if(!count($errorsDoublons)) $errorsDoublons->clean();
        if(!count($this->erreurs->$doublon_type)) $this->erreurs->$doublon_type->clean();
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
