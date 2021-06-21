<?php
/**
 * Model for DSNegoce
 *
 */

class DSNegoce extends BaseDSNegoce implements InterfaceDeclarantDocument {

	protected $declarant_document = null;

	public function __construct() {
			parent::__construct();
			$this->initDocuments();
	}

	protected function initDocuments() {
			$this->declarant_document = new DeclarantDocument($this);
	}

	public function constructId()
	{
		$this->set('_id', DSNegoceUploadClient::TYPE_MODEL.'-' . $this->identifiant . '-' . $this->getPeriode());
	}

	public function getPeriode()
	{
		return str_replace('-', '', $this->date_stock);
	}

	public function initDoc($etablissement, $date = null)
	{
			$this->identifiant = (is_object($etablissement))? $etablissement->identifiant : $etablissement;
			$cm = new CampagneManager('08-01');
			$this->date_stock = DSNegoceClient::getDateDeclaration($date);
			$tabDateStock = explode('-', $this->date_stock);
			$this->campagne = $cm->getCampagneByDate($this->date_stock);
			$this->millesime = $tabDateStock[0]-1;
			$this->constructId();
	}

	/*** DECLARANT ***/

	public function getEtablissementObject() {
		return EtablissementClient::getInstance()->findByIdentifiant($this->identifiant);
	}

	public function storeDeclarant() {
			$this->declarant_document->storeDeclarant();
			$etablissement = $this->getEtablissementObject();
			$declarant = $this->getDeclarant();
			$declarant->famille = $etablissement->famille;
			$declarant->sous_famille = $etablissement->sous_famille;
	}

	/*** FIN DECLARANT ***/

}
