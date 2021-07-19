<?php
/**
 * Model for DS
 *
 */

class DS extends BaseDS implements InterfaceDeclarantDocument, InterfaceVersionDocument {

	protected $declarant_document = null;
	protected $version_document = null;

	public function __construct() {
			parent::__construct();
			$this->initDocuments();
	}

	protected function initDocuments() {
			$this->declarant_document = new DeclarantDocument($this);
			$this->version_document = new VersionDocument($this);
	}

	public function constructId()
	{
		$this->set('_id', DSClient::makeId($this->identifiant, $this->getDateStock(), $this->version));
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
			$this->teledeclare = (int)$teledeclare;
			$this->constructId();
			$this->storeDeclarant();
			$this->storeProduits();
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

	public function storeProduits() {
		$doc = $this->getDocumentRepriseProduits();
		if ($doc) {
			$this->docid_origine_reprise_produits = $doc->_id;
			foreach($doc->getProduitsCepages() as $produitCepage) {
				$hashCepage = str_replace(['/declaration/','declaration/'], '', $produitCepage->getHash());
				$produit = $this->declaration->add($hashCepage);
				$produit->libelle = $produitCepage->getLibelle();
				foreach($produitCepage->getProduits() as $detail) {
					$produitDetail = $produit->detail->add($detail->getKey());
					$produitDetail->denomination_complementaire = trim(str_replace($produitCepage->getLibelle(), '', $detail->getLibelle()));
					$produitDetail->stock_initial_millesime_courant = $detail->total;
				}
			}
		}
	}

	public function getDocumentRepriseProduits()
	{
			return DSClient::getDocumentRepriseProduits($this->identifiant, $this->date_stock);
	}

	public function devalidate()
	{
		$this->valide->date_saisie = null;
		$this->valide->date_signee = null;
		$this->valide->identifiant = null;
	}

	public function validate()
	{
		$this->valide->date_saisie = date('Y-m-d');
		$this->valide->date_signee = $this->valide->date_saisie;
		$this->valide->identifiant = $this->identifiant;
	}

	public function isValidee()
	{
		return preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $this->valide->date_saisie);
	}

	public function isValidable()
	{
		foreach($this->declaration as $k => $v) {
			foreach($v->detail as $sk => $sv) {
				if ($sv->stock_initial_millesime_courant > 0 && $sv->stock_declare_millesime_courant === null) {
					return false;
				}
			}
		}
		return true;
	}

	public function getDateVersion()
	{
		$str = str_replace('-', '', $this->getDateStock());
		if ($this->version) {
			$str .= '-'.$this->version;
		}
		return $str;
	}

	/*     * ** VERSION *** */

	public static function buildVersion($rectificative, $modificative) {
			return VersionDocument::buildVersion($rectificative, $modificative);
	}

	public static function buildRectificative($version) {
			return VersionDocument::buildRectificative($version);
	}

	public static function buildModificative($version) {
			return VersionDocument::buildModificative($version);
	}

	public function getVersion() {
			return $this->_get('version');
	}

	public function hasVersion() {
			return $this->version_document->hasVersion();
	}

	public function isVersionnable() {
			if (!$this->isValidee()) {
					return false;
			}
			return $this->version_document->isVersionnable();
	}

	public function getRectificative() {
			return $this->version_document->getRectificative();
	}

	public function isRectificative() {
			return $this->version_document->isRectificative();
	}

	public function isRectifiable() {
			return $this->version_document->isRectifiable();
	}

	public function getModificative() {
			return $this->version_document->getModificative();
	}

	public function isModificative() {
			return $this->version_document->isModificative();
	}

	public function isModifiable() {
			return $this->version_document->isModifiable();
	}

	public function getPreviousVersion() {
		return $this->version_document->getPreviousVersion();
	}

	public function getMasterVersionOfRectificative() {
		$master = $this->findMaster();
		return $master->version;
	}

	public function needNextVersion() {
			return $this->version_document->needNextVersion();
	}

	public function getMaster() {
			return $this->version_document->getMaster();
	}

	public function isMaster() {
			return $this->version_document->isMaster();
	}

	public function findMaster() {
			return DSClient::getInstance()->findMasterByIdentifiantAndDate($this->identifiant, $this->getDateStock());
	}

	public function findDocumentByVersion($version) {
			return DSClient::getInstance()->find(DSClient::makeId($this->identifiant, $this->getDateStock(), $version));
	}

	public function getMother() {
			return $this->version_document->getMother();
	}

	public function motherGet($hash) {
			return $this->version_document->motherGet($hash);
	}

	public function motherExist($hash) {
			return ($this->getMother())? $this->version_document->motherExist($hash) : false;
	}

	public function motherHasChanged() {
			if (count($this->declaration) != count($this->getMother()->declaration)) {
					return true;
			}
			foreach ($this->declaration as $key => $value) {
				foreach($value->detail as $subkey => $subvalue) {
					if (!$this->getMother()->exist($subvalue->getHash())) {
						return true;
					}
					$old = $this->getMother()->get($subvalue->getHash());
					if ($subvalue->stock_declare_millesime_courant != $old->stock_declare_millesime_courant)
						return true;
					if ($subvalue->dont_vraclibre_millesime_courant != $old->dont_vraclibre_millesime_courant)
						return true;
					if ($subvalue->stock_declare_millesime_anterieur != $old->stock_declare_millesime_anterieur)
						return true;
					if ($subvalue->dont_vraclibre_millesime_anterieur != $old->dont_vraclibre_millesime_anterieur)
						return true;
				}
			}
			return false;
	}

	public function getDiffWithMother() {
			return $this->version_document->getDiffWithMother();
	}

	public function isModifiedMother($hash_or_object, $key = null) {
			return $this->version_document->isModifiedMother($hash_or_object, $key);
	}

	public function generateRectificative($force = false) {
			return $this->version_document->generateRectificative($force);
	}

	public function generateModificative() {
			return $this->version_document->generateModificative();
	}

	public function generateNextVersion() {
			if (!$this->hasVersion()) {
					$next = $this->version_document->generateModificativeSuivante();
			} else {
					$next = $this->version_document->generateNextVersion();
			}
			if (!$next) {
				return null;
			}
			return $next;
	}

	public function listenerGenerateVersion($document) {
			$document->devalidate();
	}

	public function listenerGenerateNextVersion($document) {
	}

	public function getSuivante() {
			return $this->findMaster();
	}

}
