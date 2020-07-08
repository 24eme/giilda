<?php

/**
 * Model for Subvention
 *
 */
class Subvention extends BaseSubvention  {

    public function __construct() {
        parent::__construct();
    }

    public function constructId() {
        $this->set('_id', 'SUBVENTION-'.$this->identifiant.'-'.$this->operation);
    }


    public function storeDossier($file) {
  		if (!is_file($file)) {
  			throw new sfException($file." n'est pas un fichier valide");
  		}
  		$pathinfos = pathinfo($file);
  		$extension = (isset($pathinfos['extension']) && $pathinfos['extension'])? strtolower($pathinfos['extension']): null;
  		$fileName = ($extension)? uniqid().'.'.$extension : uniqid();


  			$mime = mime_content_type($file);
  			$this->storeAttachment($file, $mime, $fileName);

      return true;
  	}

}
