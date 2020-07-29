<?php

/**
 * Model for Subvention
 *
 */
class Subvention extends BaseSubvention implements InterfaceDeclarantDocument  {

    protected $archivage_document = null;
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
        $this->archivage_document = new ArchivageDocument($this);
    }

    public function constructId() {
        $this->set('_id', 'SUBVENTION-'.$this->identifiant.'-'.$this->operation);
    }

    public function reouvrir(){
        $this->version+=1;
        $this->validation_date = null;
        $this->statut = null;
        $this->signature_date = null;
    }

    public function updateNoeudSchema($key) {
        foreach($this->getNoeudSchema($key) as $categorie => $items) {
            if(preg_match("/_libelle$/", $categorie)) {
                continue;
            }
            $this->$key->add($categorie);
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

    public function getNoeudSchema($noeud) {
        $nameGetFct = 'get'.ucfirst($noeud).'Schema';
        return $this->getConfiguration()->$nameGetFct($this->operation);
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

    public function getDefaultXlsPath(){

      return SubventionClient::getInstance()->getDefaultXlsPath($this->operation);
    }

    public function hasDefaultXlsPath(){

      return file_exists($this->getDefaultXlsPath());
    }


    public function getXlsPublicName(){

      return "formulaire_subvention_".strtolower($this->operation)."_".$this->identifiant.".xlsx";
    }

  public function getFileName(){
    return SubventionClient::getInstance()->getXlsFileName($this->operation);
  }


    public function hasXls(){
      return $this->exist('_attachments') && $this->_attachments->exist($this->getFileName());
    }

    public function validate() {
        $this->statut = SubventionClient::STATUT_VALIDE;
        $this->signature_date = date('Y-m-d');
    }

    public function validateInterpro($statut) {
        $this->statut = $statut;
        $this->add('validation_date', date('Y-m-d H:m:s'));
        $this->archivage_document->archiver();
    }

    public function isValideInterpro() {
      return $this->isApprouve() || $this->isRefuse();
    }


    public function isApprouve(){
      return $this->exist('statut') && ($this->statut == SUBVENTIONCLIENT::STATUT_APPROUVE);
    }

    public function isApprouvePartiellement(){
      return $this->exist('statut') && ($this->statut == SUBVENTIONCLIENT::STATUT_APPROUVE_PARTIELLEMENT);
    }

    public function isRefuse(){
      return $this->exist('statut') && ($this->statut == SUBVENTIONCLIENT::STATUT_REFUSE);
    }

    public function isValide(){
      return $this->exist('statut') && ($this->statut == SUBVENTIONCLIENT::STATUT_VALIDE);
    }

    public function getStatutLibelle()
    {
      if(!$this->exist('statut') || !$this->statut){
        return "En cours de saisie";
      }
      return SubventionClient::$statuts[$this->statut];
    }

    protected function preSave() {
        if($this->operation && !$this->exist('campagne_archive')) {
            $this->add('campagne_archive', $this->operation);
        }
        $this->archivage_document->preSave();
    }

    public function dosave(){
      $this->add('date_modification', date('Y-m-d H:m:s'));
    }

    /*     * * ARCHIVAGE ** */

    public function getNumeroArchive() {

        return $this->_get('numero_archive');
    }

    public function isArchivageCanBeSet() {

      return $this->exist("validation_date") && $this->validation_date;
    }

    /*     * * FIN ARCHIVAGE ** */

}
