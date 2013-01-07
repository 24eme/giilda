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
        return CompteClient::getInstance()->find($this->getSociete()->compte_societe);
    }

    public function getSociete() {
        return SocieteClient::getInstance()->find($this->id_societe);
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

    public function addLiaison($type, $etablissement) {
        if (!in_array($type, EtablissementClient::listTypeLiaisons()))
            throw new sfException("liaison type \"$type\" unknown");
        $liaison = $this->liaisons_operateurs->add($type . '_' . $etablissement->_id);
        $liaison->type_liaison = $type;
        $liaison->id_etablissement = $etablissement->_id;
        $liaison->libelle_etablissement = $etablissement->nom;
        return $liaison;
    }

    public function isNegociant() {
        return ($this->famille == EtablissementFamilles::FAMILLE_NEGOCIANT);
    }
    
    public function isCourtier() {
        return ($this->famille == EtablissementFamilles::FAMILLE_COURTIER);
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

    public function isInterLoire() {
        return ($this->region != EtablissementClient::REGION_HORSINTERLOIRE);
    }

    public function save($fromsociete = false, $fromclient = false) {
        if ($this->recette_locale->id_douane) {
            $soc = SocieteClient::getInstance()->find($this->recette_locale->id_douane);
            if ($soc && $this->recette_locale->nom != $soc->raison_sociale) {
                $this->recette_locale->nom = $soc->raison_sociale;
            }
        }
        if (!$this->famille) {
            $this->famille = EtablissementFamilles::FAMILLE_PRODUCTEUR;
        }
        if (!$this->sous_famille) {
            $this->sous_famille = EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE;
        }
        $soc = SocieteClient::getInstance()->find($this->id_societe);
        if (!$soc)
            throw new sfException("$id n'est pas une société connue");
        $this->cooperative = $soc->cooperative;

	if (!$fromclient) {
	  if ($this->siege->adresse) {
	    if (!$this->compte) {
	      $compte = CompteClient::getInstance()->createCompteFromEtablissement($this);
	      $compte->save(true, true);
	      $this->compte = $compte->_id;
	    }else{
	      $compte = $this->getContact();
	      $compte->updateFromEtablissement($e);
	      $compte->save(true, true);
	    }
	  }
	}

        parent::save();

        if (!$fromsociete) {
            $soc->addEtablissement($this);
            $soc->save(true);
        }
    }

    public function setIdSociete($id) {
        $soc = SocieteClient::getInstance()->find($id);
        if (!$soc)
            throw new sfException("$id n'est pas une société connue");
        $this->_set("id_societe", $id);
    }

    public function __toString() {

        return sprintf('%s (%s)', $this->nom, $this->identifiant);
    }

    public function getBailleurs() {
        $bailleurs = array();
        if (!(count($this->liaisons_operateurs)))
            return $bailleurs;
        $liaisons = $this->liaisons_operateurs;
        foreach ($liaisons as $key => $liaison) {
            if ($liaison->type_liaison == EtablissementClient::TYPE_LIAISON_BAILLEUR)
                $bailleurs[$key] = $liaison;
        }
        return $bailleurs;
    }

    public function findBailleurByNom($nom) {
        $bailleurs = $this->getBailleurs();
        foreach ($bailleurs as $key => $liaison) {
            if ($liaison->libelle_etablissement == $nom)
                return EtablissementClient::getInstance()->find($liaison->id_etablissement);
            if ($liaison->exist('aliases'))
                foreach ($liaison->aliases as $alias) {
                    if (strtoupper($alias) == strtoupper($nom))
                        return EtablissementClient::getInstance()->find($liaison->id_etablissement);
                }
        }
        return null;
    }
    
    public function addAliasForBailleur($identifiant_bailleur,$alias) {
        $bailleurNameNode = EtablissementClient::TYPE_LIAISON_BAILLEUR.'_'.$identifiant_bailleur;
        if(!$this->liaisons_operateurs->exist($bailleurNameNode))
            throw new sfException("La liaison avec le bailleur $identifiant_bailleur n'existe pas");
        $node = $this->liaisons_operateurs->$bailleurNameNode;
        if(!$node->exist('aliases'))
            $node->add('aliases');
        $node->aliases->add($alias,$alias);
    }

}
