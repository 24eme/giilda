<?php

/**
 * Model for Subvention
 *
 */
class Subvention extends BaseSubvention implements InterfaceDeclarantDocument  {

    protected $declarant_document = null;

    public function __construct() {
        parent::__construct();
        $this->initDocuments();
    }

    public function __clone() {
        parent::__clone();
        $this->initDocuments();
    }

    protected function initDocuments() {
        $this->declarant_document = new DeclarantDocument($this);
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

    public function storeDeclarant() {
        $this->declarant_document->storeDeclarant();
    }

    public function getEtablissementObject() {
        return $this->getEtablissement();
    }

    public function getEtablissement() {
        return EtablissementClient::getInstance()->find($this->identifiant);
    }

}
