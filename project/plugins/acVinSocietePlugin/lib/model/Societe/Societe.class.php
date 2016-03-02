<?php

/**
 * Model for Societe
 *
 */
class Societe extends BaseSociete implements InterfaceCompteGenerique {

    private $changedCooperative = null;
    private $changedStatut = null;

    public function constructId() {
        $this->set('_id', 'SOCIETE-' . $this->identifiant);
    }

    public function removeContact($idContact) {
        if ($this->contacts->exist($idContact)) {
            $this->contacts->remove($idContact);
        }
    }

    public function isInCreation() {

        return $this->statut == SocieteClient::STATUT_EN_CREATION;
    }

    public function addNewEnseigne() {
        $this->enseignes->add(count($this->enseignes), "");
    }

    public function getInterlocuteursWithOrdre() {
        foreach ($this->contacts as $key => $interlocuteur) {
            if (is_null($interlocuteur->ordre))
                $interlocuteur->ordre = 2;
        }
        return $this->contacts;
    }

    public function getMaxOrdreContacts() {
        $max = 0;
        foreach ($this->contacts as $contact) {
            if ($max < $contact->ordre)
                $max = $contact->ordre;
        }
        return $max;
    }

    public function hasChais() {
        return count($this->etablissements);
    }

    public function canHaveChais() {
        return in_array($this->type_societe, SocieteClient::getSocieteTypesWithChais());
    }

    public function getFamille() {
        if (!$this->canHaveChais())
            throw new sfException('La societe ' . $this->identifiant . " ne peut pas avoir famille n'ayant pas d'établissement");
        return $this->getTypeSociete();
    }

    public function getRegionViticole($throwexception = true) {
        if (!$this->isTransaction()) {
            return '';
        }
        $regions = $this->getRegionsViticoles($throwexception);
        if (count($regions) > 1) {
            if ($throwexception) {
                throw new sfException("La societe " . $this->identifiant . " est reliée des établissements de plusieurs régions viticoles, ce qui n'est pas permis");
            }
            return array_shift($regions);
        }
        if (!count($regions)) {
            if ($throwexception) {
                throw new sfException("La societe " . $this->identifiant . " n'a pas de région viti :(");
            }
            return '';
        }
        return array_shift($regions);
    }

    private function getRegionsViticoles($excludeSuspendus = true) {
        $regions = array();
        foreach ($this->getEtablissementsObj() as $id => $e) {
            if ($e->etablissement->isActif()) {
                $regions[$e->etablissement->region] = $e->etablissement->region;
            }
        }
        //Si tous suspendus que !excludeSuspendus, on va tout de même chercher des régions
        if (!count($regions) && !$excludeSuspendus) {
            foreach ($this->getEtablissementsObj() as $id => $e) {
                $regions[$e->etablissement->region] = $e->etablissement->region;
            }
        }
        return $regions;
    }

    public function getEtablissementsObj($withSuspendu = true) {
        $etablissements = array();
        foreach ($this->etablissements as $id => $obj) {
            $etb = EtablissementClient::getInstance()->find($id);
            if (!$withSuspendu) {
                if (!$etb->isActif()) {
                    continue;
                }
            }
            $etablissements[$id] = new stdClass();
            $etablissements[$id]->etablissement = $etb;
            $etablissements[$id]->ordre = $obj->ordre;
        }
        return $etablissements;
    }

    public function getEtablissementPrincipal() {
        $etablissements = $this->getEtablissementsObj();
        if (!count($etablissements)) {
            return null;
        }
        foreach ($etablissements as $id => $etbObj) {
            $etablissement = $etbObj->etablissement;
            $compte = CompteClient::getInstance()->find($etablissement->compte);
            if ($compte->compte_type == CompteClient::TYPE_COMPTE_SOCIETE) {
                return $etablissement;
            }
        }
        $etbObj = array_shift($etablissements);
        return $etbObj->etablissement;
    }

    public function getContactsObj() {
        $contacts = array();
        foreach ($this->contacts as $id => $obj) {
            $contacts[$id] = CompteClient::getInstance()->find($id);
        }
        return $contacts;
    }

    public function getComptesAndEtablissements() {
        $contacts = array();

        foreach ($this->contacts as $id => $obj) {
            $contacts[$id] = CompteClient::getInstance()->find($id);
        }
        foreach ($this->etablissements as $id => $obj) {
             $contacts[$id] = EtablissementClient::getInstance()->find($id);
        }

        return $contacts;
    }

    public function addEtablissement($e, $ordre = null) {
        if (!$this->etablissements->exist($e->_id)) {
            $this->etablissements->add($e->_id, array('nom' => $e->nom, 'ordre' => $ordre));
        } else {
            $this->etablissements->add($e->_id)->nom = $e->nom;
            if ($ordre !== null) {
                $ordre = 0;
            }
            $this->etablissements->add($e->_id)->ordre = $ordre;
        }
        if ($e->compte) {
            $this->addCompte($e->getMasterCompte(), $ordre);
        }
    }

    public function setCooperative($c) {
        $this->_set('cooperative', $c);
        foreach ($this->getEtablissementsObj() as $e) {
            $e->cooperative = $c;
        }
        $this->changedCooperative = true;
    }

    public function setStatut($s) {
        $this->_set('statut', $s);
        foreach ($this->getEtablissementsObj() as $e) {
            $e->statut = $s;
        }
        $this->changedStatut = true;
    }

    public function addCompte($c, $ordre = null) {
        if (!$this->compte_societe) {
            $this->compte_societe = $c->_id;
        }
        if (!$c->_id) {
            return;
        }

        if (!$ordre) {
            $ordre = 0;
        }

        $cid = 'COMPTE-' . $c->identifiant;
        if (!$this->contacts->exist($c->_id)) {
            $this->contacts->add($cid, array('nom' => $c->nom_a_afficher, 'ordre' => $ordre));
        } else {
            $this->contacts->add($cid)->nom = $c->nom_a_afficher;
            $this->contacts->add($cid)->ordre = $ordre;
        }
    }

    public static function cmpOrdreContacts($a, $b) {
        if ($a->ordre == $b->ordre) {
            return 0;
        }
        return (intval($a->ordre) < intval($b->ordre)) ? -1 : 1;
    }

    public function getMasterCompte() {
        if(!$this->compte_societe) {

            return null;
        }

        return CompteClient::getInstance()->find($this->compte_societe);
    }

    public function getContact() {

        return $this->getMasterCompte();
    }

    public function isManyEtbPrincipalActif() {
        $cptActif = 0;
        foreach ($this->getEtablissementsObj() as $etb) {
            if ($etb->etablissement->isSameCompteThanSociete() && $etb->etablissement->isActif()) {
                $cptActif++;
            }
            if ($cptActif > 1)
                return true;
        }
        return false;
    }

    public function isOperateur() {
        return SocieteClient::TYPE_OPERATEUR == $this->type_societe;
    }

    public function isTransaction() {
        return $this->isNegoOrViti() || $this->isCourtier();
    }

    public function isNegoOrViti() {
        return ($this->type_societe == SocieteClient::SUB_TYPE_VITICULTEUR) || ($this->type_societe == SocieteClient::SUB_TYPE_NEGOCIANT);
    }

    public function isCourtier() {
        return $this->type_societe == SocieteClient::TYPE_COURTIER;
    }

    public function isViticulteur() {
        if ($this->type_societe != SocieteClient::TYPE_OPERATEUR) {
            return false;
        }

        foreach ($this->getEtablissementsObj() as $id => $e) {
            if ($e->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR) {
                return true;
            }
        }
        return false;
    }

    public function isNegociant() {
        if ($this->type_societe != SocieteClient::TYPE_OPERATEUR) {
            return false;
        }

        foreach ($this->getEtablissementsObj() as $id => $e) {
            if ($e->famille == EtablissementFamilles::FAMILLE_NEGOCIANT) {
                return true;
            }
        }
        return false;
    }

    public function isActif() {
        return $this->exist('statut') && $this->statut === EtablissementClient::STATUT_ACTIF;
    }
    
     public function isSuspendu() {
        return $this->exist('statut') && $this->statut === EtablissementClient::STATUT_SUSPENDU;
    }

    public function hasNumeroCompte() {
        return ($this->code_comptable_client || $this->code_comptable_fournisseur);
    }

    public function getSiegeAdresses() {
        $a = $this->siege->adresse;
        if ($this->siege->exist("adresse_complementaire")) {
            $a .= ' ; ' . $this->siege->adresse_complementaire;
        }
        return $a;
    }

    public function synchroFromCompte() {
        $compte = $this->getMasterCompte();

        if (!$compte) {
            return;
        }

        if ($compte->exist("adresse_complementaire")) {
            $this->siege->add("adresse_complementaire", $compte->adresse_complementaire);
        }
        $this->siege->adresse = $compte->adresse;
        $this->siege->code_postal = $compte->code_postal;
        $this->siege->commune = $compte->commune;
        $this->email = $compte->email;
        $this->fax = $compte->fax;
        $this->telephone = ($compte->telephone_bureau) ? $compte->telephone_bureau : $compte->telephone_mobile;

        return $this;
    }

    protected function createCompteSociete() {
        if ($this->compte_societe) {
            return;
        }

        $compte = CompteClient::getInstance()->findOrCreateCompteSociete($this);
        $this->compte_societe = $compte->_id;
        $compte->nom = $this->raison_sociale;
        $compte->updateNomAAfficher();
        $compte->statut = $this->statut;
        $compte->mot_de_passe = "{TEXT}" . sprintf("%04d", rand(0, 9999));
        $compte->addOrigine($this->_id);
        $this->addCompte($compte, -1);
        return $compte;
    }

    protected function synchroAndSaveEtablissement() {
        if (($this->changedCooperative) || ($this->changedStatut)) {
            foreach ($this->getEtablissementsObj() as $id => $e) {
                $e->etablissement->cooperative = $this->cooperative;
                $e->etablissement->statut = $this->statut;
                $e->etablissement->save(true);
            }
        }
    }

    public function getDateCreation() {
        $this->add('date_creation');
        return $this->_get('date_creation');
    }

    public function getDateModification() {
        $this->add('date_modification');
        return $this->_get('date_modification');
    }

    public function save() { 
        $this->add('date_modification', date('Y-m-d'));

        $compteMaster = $this->getMasterCompte();

        if(!$compteMaster) {
            $compteMaster = $this->createCompteSociete();
        }

        if($this->isInCreation()){
            $this->setStatut(SocieteClient::STATUT_ACTIF);
        }

        parent::save();

        if($compteMaster->isNew()) {
            $compteMaster->save();
        }

        foreach($this->getComptesAndEtablissements() as $id => $compteOrEtablissement) {
            $needSave = false;
            if(CompteGenerique::isSameAdresseComptes($compteOrEtablissement, $compteMaster)) {
                $this->pushAdresseTo($compteOrEtablissement);
                $needSave = true;
            }
            if(CompteGenerique::isSameContactComptes($compteOrEtablissement, $compteMaster)) {
                $this->pushContactTo($compteOrEtablissement);
                $needSave = true;
            }

            if($needSave) {
                $compteOrEtablissement->save();
            }
        }
    }

    public function isPresse() {
        return $this->exist('type_societe') && ($this->type_societe == SocieteClient::TYPE_PRESSE);
    }

    public function isInstitution() {
        return $this->exist('type_societe') && ($this->type_societe == SocieteClient::SUB_TYPE_INSTITUTION);
    }

    public function isSyndicat() {
        return $this->exist('type_societe') && ($this->type_societe == SocieteClient::SUB_TYPE_SYNDICAT);
    }

    public function getEmailTeledeclaration() {
        if ($this->exist('teledeclaration_email') && $this->teledeclaration_email) {
            return $this->teledeclaration_email;
        }
        if ($this->exist('email') && $this->email) {
            return $this->email;
        }
        $compteSociete = $this->getMasterCompte();
        if ($compteSociete->exist('societe_information') && $compteSociete->societe_information->exist('email') && $compteSociete->societe_information->email) {
            return $compteSociete->societe_information->email;
        }
        return $compteSociete->email;
    }

    public function setEmailTeledeclaration($email) {
        $this->add('teledeclaration_email', $email);
    }

    public function getCommentaire() {
        $c = $this->_get('commentaire');
        $c1 = $this->getContact()->get('commentaire');
        if ($c && $c1) {
            return $c . "\n" . $c1;
        }
        if ($c) {
            return $c;
        }
        if ($c1) {
            return $c1;
        }
    }

    public function addCommentaire($s) {
        $c = $this->get('commentaire');
        if ($c) {
            return $this->_set('commentaire', $c . "\n" . $s);
        }
        return $this->_set('commentaire', $s);
    }

}
