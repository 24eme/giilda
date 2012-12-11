<?php

class Etablissement extends BaseEtablissement {

    protected $_interpro = null;
    protected $droit = null;

    const STATUT_ACTIF = "ACTIF";
    const STATUT_ARCHIVE = "ARCHIVE";
    const STATUT_DELIE = "DELIE";
    const STATUT_CSV = "CSV";

    /**
     * @return _Compte
     */
    public function getInterproObject() {
        if (is_null($this->_interpro)) {
            $this->_interpro = InterproClient::getInstance()->find($this->interpro);
        }

        return $this->_interpro;
    }

    public function constructId() {
        $this->set('_id', 'ETABLISSEMENT-' . $this->identifiant);
    }

    public function getAllDRM() {
        return acCouchdbManager::getClient()->startkey(array($this->identifiant, null))
                        ->endkey(array($this->identifiant, null))
                        ->getView("drm", "all");
    }

    private function cleanPhone($phone, $idcompte) {
        $phone = preg_replace('/[^0-9\+]+/', '', $phone);
        $phone = preg_replace('/^00/', '+', $phone);
        $phone = preg_replace('/^0/', '+33', $phone);

        if (strlen($phone) == 9 && preg_match('/^[64]/', $phone))
            $phone = '+33' . $phone;

        if (!preg_match('/^\+/', $phone) || (strlen($phone) != 12 && preg_match('/^\+33/', $phone)))
            echo("$phone n'est pas un téléphone correct pour " . $this->_id . "\n");

        return $phone;
    }

    public function getContact() {
        if ($this->compte)
            return CompteClient::getInstance()->find($this->compte);
        return CompteClient::getInstance()->find(SocieteClient::getInstance()->find($this->id_societe)->compte_societe);
    }

    public function contactIsSocieteContact() {
        return is_null($this->compte);
    }

    public function setFax($fax) {
        if ($fax)
            $this->_set('fax', $this->cleanPhone($fax));
    }

    public function setTelephone($phone, $idcompte = null) {
        if ($phone)
            $this->_set('telephone', $this->cleanPhone($phone));
    }

    public function getDenomination() {

        return ($this->nom) ? $this->nom : $this->raison_sociale;
    }

    public function getFamilleType() {
        $familleType = array(EtablissementFamilles::FAMILLE_PRODUCTEUR => 'vendeur',
            EtablissementFamilles::FAMILLE_NEGOCIANT => 'acheteur',
            EtablissementFamilles::FAMILLE_COURTIER => 'mandataire');
        return $familleType[$this->famille];
    }

    public function getDepartement() {
        if ($this->siege->code_postal) {
            return substr($this->siege->code_postal, 0, 2);
        }
        return null;
    }

    public function getDroit() {
        if (is_null($this->droit)) {

            $this->droit = new EtablissementDroit($this);
        }

        return $this->droit;
    }

    public function hasDroit($droit) {

        return $this->getDroit()->has($droit);
    }

    public function getDroits() {
        return EtablissementFamilles::getDroitsByFamilleAndSousFamille($this->famille, $this->sous_famille);
    }

    public function save() {
        
        
        if ($this->recette_locale->id_douane) {
		$soc = SocieteClient::getInstance()->find($this->recette_locale->id_douane);
		if ($soc) {
			$this->recette_locale->nom = $soc->raison_sociale;
		}
        }
        if (!$this->famille) {
            $this->famille = EtablissementFamilles::FAMILLE_PRODUCTEUR;
        }
        if (!$this->sous_famille) {
            $this->sous_famille = EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE;
        }
        
        parent::save();

	$soc = SocieteClient::getInstance()->find($this->id_societe);
        if(!$soc)
                throw new sfException("$id n'est pas une société connue");
        $soc->addEtablissement($this);
	$soc->save();

    }

    public function setIdSociete($id) {
	$soc = SocieteClient::getInstance()->find($id);
	if(!$soc)
		throw new sfException("$id n'est pas une société connue");
	$this->_set("id_societe", $id);
    }

    public function isInterLoire() {
      return ($this->region == EtablissementClient::REGION_HORSINTERLOIRE);
    }

    public function __toString() {

        return sprintf('%s (%s)', $this->nom, $this->identifiant);
    }

}
