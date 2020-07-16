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
    
    public function getConfiguration() {
        return SubventionConfiguration::getInstance();
    }

    protected function initDocuments() {
        $this->declarant_document = new DeclarantDocument($this);
    }

    public function constructId() {
        $this->set('_id', 'SUBVENTION-'.$this->identifiant.'-'.$this->operation);
    }

    public function updateInfosSchema() {
        foreach($this->getInfosSchema() as $categorie => $items) {
            $this->infos->add($categorie);
        }
    }

    public function storeDossier($file) {
  		if (!is_file($file)) {
  			throw new sfException($file." n'est pas un fichier valide");
  		}
  		$pathinfos = pathinfo($file);
  		$extension = (isset($pathinfos['extension']) && $pathinfos['extension'])? strtolower($pathinfos['extension']): 'xlsx';
  		$fileName = "formulaire_subvention_".strtolower($this->operation).'.'.$extension;


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

    public function getInfosSchema() {

        return SubventionConfiguration::getInstance()->getInfosSchema($this->operation);
    }

    public function getXls() {
        if($path = $this->getXlsPath()){
          return file_get_contents($path);
        }
        return "";
    }

    public function getXlsPath() {
        if(!$this->hasXls() && file_exists($this->getDefaultXlsPath())){
          return $this->getDefaultXlsPath();
        }
        $uri = $this->getAttachmentUri($this->getFileName());
        if ($uri) {
          return $uri;
        }
        return "";
    }

    public function getFileName(){

      return "formulaire_subvention_".strtolower($this->operation).".xlsx";
    }

    public function getXlsPublicName(){

      return "formulaire_subvention_".strtolower($this->operation)."_".$this->identifiant.".xlsx";
    }


    public function getDefaultXlsPath(){

      return realpath(dirname(__FILE__) . "/../../../../../data/subventions/".$this->getFileName());
    }

    public function hasXls(){
      return $this->exist('_attachments') && $this->_attachments->exist($this->getFileName());
    }

    public function validate() {
        $this->getObject()->remove('engagements');
        $this->getObject()->add('engagements');
        $engagements = sfConfig::get('subvention_configuration_engagements');
	    foreach ($engagements as $key => $libelle) {
	        if (isset($values["engagement_$key"]) && $values["engagement_$key"]) {
	            $this->engagements->add($key, true);
	        }
	    }
        $this->statut = SubventionClient::STATUT_VALIDE;
        $this->signature_date = date('Y-m-d');
    }

    public function validateInterpro($statut) {
        $this->statut = $statut;
    }

}
