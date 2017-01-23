<?php

/**
 * Model for CSV
 *
 */
class CSV extends BaseCSV {

    public function __construct() {
        parent::__construct();
    }

    public function getFileContent() {
        return file_get_contents($this->getAttachmentUri($this->getFileName()));
    }

    public function getFileName() {
        return 'import_edi_' . $this->identifiant . '_' . $this->periode . '.csv';
    }

    public function hasErreurs($level = null) {
      if(!$level){
        return count($this->erreurs);
      }
      foreach ($this->erreurs as $erreur) {
        if($erreur->exist('level') && ($erreur->level == $level)){
          return true;
        }
      }
      return false;
    }

    public function addErreur($erreur) {
        $erreurNode = $this->erreurs->getOrAdd($erreur->num_ligne);
        $erreurNode->num_ligne = $erreur->num_ligne;
        $erreurNode->csv_erreur = $erreur->erreur_csv;
        $erreurNode->diagnostic = $erreur->raison;
        if($erreur->level){
          $erreurNode->level = $erreur->level;
        }
        return $erreurNode;
    }

    public function clearErreurs() {
        $this->remove('erreurs');
        $this->add('erreurs');
        $this->statut = null;
    }

    public function getLevel(){
      if(!$this->exist('level')){
        return CSVClient::LEVEL_WARNING;
      }
      return $this->_get('level');
    }

}
