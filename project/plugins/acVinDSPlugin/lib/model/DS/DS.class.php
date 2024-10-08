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
		$regex = DSConfiguration::getInstance()->getProductHashRegexFilter();
		$interpro = DSConfiguration::getInstance()->getProductDetailInterpro();
        $exceptionProduit = DSConfiguration::getInstance()->exceptionProduit();
		if ($doc) {
			$this->docid_origine_reprise_produits = $doc->_id;
			foreach($doc->getProduitsCepages() as $produitCepage) {
				$hashCepage = str_replace(['/declaration/','declaration/'], '', $produitCepage->getHash());
                $isException = ($exceptionProduit && (strpos($produitCepage->getHash(), $exceptionProduit) !== false));
				if (!$isException && $regex && !preg_match($regex, $hashCepage)) {
					continue;
				}
				foreach($produitCepage->getProduits() as $detail) {
					if (!$isException && $interpro && $detail->exist('interpro') && $detail->interpro != $interpro) {
						continue;
					}
					if (!$isException && !$detail->hasCvo()) {
						continue;
					}
					$produit = $this->declaration->add($hashCepage);
					$produit->libelle = $produitCepage->getLibelle();
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
        $this->updateReferente();
	}

	public function validate()
	{
		$this->valide->date_saisie = date('Y-m-d');
		$this->valide->date_signee = $this->valide->date_saisie;
		$this->valide->identifiant = $this->identifiant;
        $this->updateReferente();
	}

    public function updateReferente()
    {
        $this->referente = ($this->isValidee())? 1 : 0;

        $master = $this->getSuivante();
        $mother = $this->getMother();

        if ($master && $master->_id === $this->_id) {
            $master = null;
        }

        if ($mother && $mother->_id === $mother->_id) {
            $mother = null;
        }

        if ($master && $master->isValidee()) {
            $this->referente = 0;
        }

    	if ($mother) {
            if ($this->referente) {
    		    $mother->referente = 0;
            } elseif ($master && $master->isValidee()) {
                $mother->referente = 0;
            } else {
                $mother->referente = 1;
            }
    		$mother->save();
    	}
    }

	public function isValidee()
	{
		return preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $this->valide->date_saisie);
	}

	public function isValidable()
	{
		return (count($this->getPointsBloquants()) > 0)? false : true;
	}

	public function getPointsBloquants() {
			$pb = array();
			foreach($this->declaration as $k => $v) {
				foreach($v->detail as $sk => $sv) {
					if ($sv->stock_initial_millesime_courant > 0 && $sv->stock_declare_millesime_courant === null) {
						$pb[] = trim($v->libelle." ".$sv->denomination_complementaire)." : Vous devez saisir le stock $this->millesime des produits, dont le stock fin de mois n'est pas nul.";
					}
					if ($sv->dont_vraclibre_millesime_courant > $sv->stock_declare_millesime_courant) {
						$pb[] = trim($v->libelle." ".$sv->denomination_complementaire)." : Le volume disponible $this->millesime ne peut pas excéder le stock $this->millesime";
					}
					if ($sv->dont_vraclibre_millesime_anterieur > $sv->stock_declare_millesime_anterieur) {
						$pb[] = trim($v->libelle." ".$sv->denomination_complementaire)." : Le volume disponible ".($this->millesime-1).", précédent et non millésimé ne peut pas excéder le stock ".($this->millesime-1).", précédent et non millésimé";
					}
				}
			}
			return $pb;
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
					if ($subvalue->stock_declare_millesime_precedent != $old->stock_declare_millesime_precedent)
						return true;
					if ($subvalue->dont_vraclibre_millesime_precedent != $old->dont_vraclibre_millesime_precedent)
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
        if ($version = $this->version_document->getNextVersion()) {
            return DSClient::getInstance()->find(DSClient::makeId($this->identifiant, $this->getDateStock(), $version));
        }
		return null;
	}

}
