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
		$this->set('_id', DSNegoceClient::makeId($this->identifiant, $this->getDateStock()));
	}

	public function getPeriode()
	{
		return substr($this->date_stock, 0, -3);
	}

	public function initDoc($etablissement, $date, $teledeclare = false)
	{
			if (!preg_match('/^[0-9]{4}-07-31$/', $date)) {
				throw new Exception('Date de stock invalide : '.$date);
			}
			$this->identifiant = (is_object($etablissement))? $etablissement->identifiant : $etablissement;
			$cm = new CampagneManager('08-01');
			$this->date_stock = $date;
			$tabDateStock = explode('-', $date);
			$this->campagne = $cm->getCampagneByDate($date);
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
