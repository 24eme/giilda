<?php

/**
 * Model for Societe
 *
 */
class Societe extends BaseSociete implements InterfaceCompteGenerique, InterfaceMandatSepaPartie {

    private $comptes = null;

    public function constructId() {
        $this->set('_id', 'SOCIETE-' . $this->identifiant);
    }

    public function removeContact($idContact) {
        if ($this->contacts->exist($idContact)) {
            $contact = $this->getCompte($idContact);
            $this->contacts->remove($idContact);
            $this->removeFromComptes($contact);
            $contact->delete();
            $contact = NULL;
        }
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

    public function setIdentifiant($identifiant) {
        $r = $this->_set('identifiant', $identifiant);

        $this->code_comptable_client = $this->getCodeComtableClient();

        return $r;
    }

    public function getCodeComptable(){
      return $this->getCodeComptableClient();
    }

    public function getCodeComtableClient() {
        if(!$this->_get('code_comptable_client') && class_exists("FactureConfiguration")) {
            return FactureConfiguration::getInstance()->getPrefixCodeComptable().$this->identifiant."";
        }

        if(!$this->_get('code_comptable_client')) {

            return $this->identifiant;
        }

        return $this->_get('code_comptable_client');
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

    public function getEtablissementsObject($withSuspendu = true) {
        $etablissements = array();
        foreach ($this->getEtablissementsObj($withSuspendu) as $id => $obj) {
            $etablissements[$id] = $obj->etablissement;
        }
        return $etablissements;
    }

    public function getEtablissementPrincipal() {
        $etablissements = $this->getEtablissementsObj(false);
        if (!count($etablissements)) {
            return null;
        }
        foreach ($etablissements as $id => $etbObj) {
            $etablissement = $etbObj->etablissement;
            $compte = $this->getCompte($etablissement->compte);
            if ($compte->compte_type == CompteClient::TYPE_COMPTE_SOCIETE) {
                return $etablissement;
            }
        }
        $etbObj = array_shift($etablissements);
        return $etbObj->etablissement;
    }

    public function getAllCompteObj() {
      if (!$this->comptes || !count($this->comptes)) {
        foreach ($this->contacts as $id => $obj) {
          $compteToAdd = CompteClient::getInstance()->find($id);
          if($compteToAdd){
            $this->addToComptes($compteToAdd);
          }
        }
      }
      return $this->comptes;
    }

    private function addToComptes($compte) {
      if (!$this->comptes) {
        $this->comptes = array();
      }
      if ($compte === null) {
          throw new sfException("Could not add NULL compte");
      }
      $this->comptes[$compte->_id] = $compte;
    }
    private function removeFromComptes($compte) {
      $this->getAllCompteObj();
      unset($this->comptes[$compte->_id]);
    }

    public function getComptesAndEtablissements() {
        $contacts = array();
        foreach ($this->getEtablissementsObj() as $id => $obj) {
          $contacts[$id] = EtablissementClient::getInstance()->find($id);
        }
        foreach ($this->getAllCompteObj() as $id => $obj) {
            $contacts[$id] = $obj;
        }

        return $contacts;
    }

    public function getComptesInterlocuteurs() {
        $Interlocuteurs = array();
        foreach ($this->getAllCompteObj() as $id => $compte) {
          if($compte->compte_type != CompteClient::TYPE_COMPTE_INTERLOCUTEUR) {
              continue;
          }
          $Interlocuteurs[$id] = $compte;
        }
        return $Interlocuteurs;
    }

    public function getCompte($id) {
        $this->getAllCompteObj();
        if (!isset($this->comptes[$id]) || !$this->comptes[$id]) {
          $this->comptes[$id] = CompteClient::getInstance()->findByIdentifiant($this->identifiant);
            //throw new sfException("Pas de compte ".$id);
        }
        return $this->comptes[$id];
    }

    public function addEtablissement($e) {
        if (!$this->etablissements->exist($e->_id)) {
            $this->etablissements->add($e->_id, array('nom' => $e->nom));
        } else {
            $this->etablissements->add($e->_id)->nom = $e->nom;

        }
	if ($e->compte) {
            $this->addCompte($e->getMasterCompte());
        }
    }

    public function removeEtablissement($e) {
      $this->etablissements->remove($e->_id);
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
            $this->addToComptes($c);
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
        return ($this->type_societe == SocieteClient::TYPE_OPERATEUR);
    }

    public function isCourtier() {
        return $this->type_societe == SocieteClient::TYPE_COURTIER;
    }

    public function isViticulteur() {
        if ($this->type_societe != SocieteClient::TYPE_OPERATEUR) {
            return false;
        }

        foreach ($this->getEtablissementsObj() as $id => $e) {
            if ($e->etablissement->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR) {
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
            if ($e->etablissement->famille == EtablissementFamilles::FAMILLE_NEGOCIANT) {
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
        return Anonymization::hideIfNeeded($a);
    }

// A VIRER
    protected function createCompteSociete() {
        if ($this->compte_societe) {
            $c = $this->getCompte($this->compte_societe);
            if ($c) {
                return $c;
            }
        }

        $compte = CompteClient::getInstance()->findOrCreateCompteSociete($this);
        $this->compte_societe = $compte->_id;
        $compte->setSociete($this);
        if($this->statut) {
            $compte->statut = $this->statut;
        } else {
            $compte->statut = CompteClient::STATUT_ACTIF;
        }
        $compte->mot_de_passe = "{TEXT}" . sprintf("%04d", rand(1000, 9999));
        $compte->addOrigine($this->_id);
        $this->addCompte($compte, -1);
        $compte->nom = $this->raison_sociale;
        $this->addToComptes($compte);
        $this->pushContactAndAdresseTo($compte);
        return $compte;
    }

    public function getDateCreation() {
        $this->add('date_creation');
        return $this->_get('date_creation');
    }

    public function getDateModification() {
        $this->add('date_modification');
        return $this->_get('date_modification');
    }

    protected function doSave() {
        $this->add('date_modification', date('Y-m-d'));
    }

    public function save() {
        if(SocieteConfiguration::getInstance()->isDisableSave()) {

            throw new Exception("L'enregistrement des sociétés, des établissements et des comptes sont désactivés");
        }

        $this->interpro = "INTERPRO-declaration";
        $compteMaster = $this->getMasterCompte();

        if (!$compteMaster) {
            $compteMaster = $this->createCompteSociete();
        }

        if(count($this->etablissements)){
          $this->type_societe = SocieteClient::TYPE_OPERATEUR;
        }else{
          $this->type_societe = SocieteClient::TYPE_AUTRE;
        }
        parent::save();

        SocieteClient::getInstance()->setSingleton($this);
        $compteMasterOrigin = clone $compteMaster;
        $this->pushToCompteOrEtablissementAndSave($compteMaster, $compteMaster);

        foreach ($this->etablissements as $id => $obj) {
            $this->pushToCompteOrEtablissementAndSave($compteMaster, EtablissementClient::getInstance()->find($id), $compteMasterOrigin);
        }

        foreach ($this->getComptesInterlocuteurs() as $id => $compte) {
            $this->pushToCompteOrEtablissementAndSave($compteMaster, $compte, $compteMasterOrigin);
        }
    }

    public function pushToCompteOrEtablissementAndSave($compteMaster, $compteOrEtablissement, $compteMasterOrigin = null) {
    $needSave = false;

    if(is_null($compteMasterOrigin)) {
        $compteMasterOrigin = $compteMaster;
    }
    if (!$compteMaster) {
      throw new sfException("compteMaster should not be NULL");
    }
    if (!$compteOrEtablissement) {
      throw new sfException("compteOrEtablissement should not be NULL");
    }
    if(!$compteOrEtablissement->isNew() && (CompteClient::getInstance()->find($compteOrEtablissement->_id)->_rev != $compteOrEtablissement->_rev)){
      $compteOrEtablissement = CompteClient::getInstance()->find($compteOrEtablissement->_id);
    }
    if ($compteMaster->_id == $compteOrEtablissement->_id) {
        if ($compteOrEtablissement->nom != $this->raison_sociale) {
          $compteOrEtablissement->nom = $this->raison_sociale;
        }
        $needSave = true;
    }

    if($compteOrEtablissement->exist('raison_sociale') && $compteOrEtablissement->raison_sociale != $this->raison_sociale){
      $compteOrEtablissement->raison_sociale  = $this->raison_sociale;
      $needSave = true;
    }

    if ($compteOrEtablissement->exist('siret') && $compteOrEtablissement->siret != $this->siret) {
      $needSave = true;
    }

    if (CompteGenerique::isSameAdresseComptes($compteOrEtablissement, $compteMasterOrigin)) {
        $ret = $this->pushAdresseTo($compteOrEtablissement);
        $needSave = $needSave || $ret;
    }
    if (CompteGenerique::isSameContactComptes($compteOrEtablissement, $compteMasterOrigin)) {
        $ret = $this->pushContactTo($compteOrEtablissement);
        $needSave = $needSave || $ret;
    }
    if ($needSave) {
        $compteOrEtablissement->save();
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

    public function getEmails(){
        return explode(';',$this->email);
    }

    public function getEmailCompta() {
        return $this->getEmail();
    }

    public function getTeledeclarationEmail() {
        if ($this->exist('teledeclaration_email') && $this->_get('teledeclaration_email')) {
            return Anonymization::hideIfNeeded($this->_get('teledeclaration_email'));
        }
        if ($compteSociete = $this->getMasterCompte()) {
	        if ($compteSociete->exist('societe_information') && $compteSociete->societe_information->exist('email') && $compteSociete->societe_information->email) {
	            return Anonymization::hideIfNeeded($compteSociete->societe_information->email);
	        }
	        return Anonymization::hideIfNeeded($compteSociete->email);
        }
        if ($this->exist('email') && $this->email) {
            return Anonymization::hideIfNeeded($this->email);
        }
        return null;
    }

    public function setEmailTeledeclaration($email) {
        $this->add('teledeclaration_email', $email);
    }

    public function getCommentaire() {
        $c = $this->_get('commentaire');
        $c1 = null;
        if ($this->getMasterCompte()) {
            $c1 = $this->getMasterCompte()->get('commentaire');
        }
        if ($c && $c1) {
            return $c . "\n" . $c1;
        }
        if ($c1) {
            return $c1;
        }
        return $c;
    }

    public function getCommentaires() {
        $lines = explode("\n", str_replace(' - ', "\n", $this->getCommentaire()));
        return array_filter($lines, fn($value) => (rtrim($value)));
    }

    public function addCommentaire($s) {
        $c = $this->get('commentaire');
        if ($c) {
            return $this->_set('commentaire', $c . "\n" . $s);
        }
        return $this->_set('commentaire', $s);
    }

    public function hasLegalSignature() {
        if ($this->exist('legal_signature'))
            return ($this->add('legal_signature')->add('v1'));
        return false;
    }

    public function delete() {
      foreach($this->getComptesAndEtablissements() as $id => $obj) {
        if ($obj) {
          $obj->delete();
        }
      }
      return parent::delete();
    }

    public function createEtablissement($famille) {
      $etablissement = new Etablissement();
      $etablissement->id_societe = $this->_id;
      $societeSingleton = SocieteClient::getInstance()->findSingleton($this->_id);
      if(!$societeSingleton) {
          throw new sfException("La société doit être créé avant de créer l'établissement");
      }
      $etablissement->setSociete($societeSingleton);
      $etablissement->identifiant = EtablissementClient::getInstance()->getNextIdentifiantForSociete($societeSingleton);
      if ($famille) {
          $etablissement->famille = $famille;
      }
      $etablissement->constructId();
      $etablissement->nom = $this->getRaisonSociale();
      return $etablissement;
    }

    public function findOrCreateCompteFromEtablissement($etablissement) {
      $compte = CompteClient::getInstance()->findByIdentifiant($etablissement->identifiant);
      if(!$compte){
        $compte = CompteClient::getInstance()->createCompteForEtablissementFromSociete($etablissement);
        $this->addCompte($compte);
      }
      return $compte;
    }

    public function switchStatusAndSave() {
      $newStatus = "";
      $this->save();

      if($this->isActif() || !$this->statut){
         $newStatus = SocieteClient::STATUT_SUSPENDU;
      }
      if($this->isSuspendu()){
         $newStatus = SocieteClient::STATUT_ACTIF;
      }
      $toberemoved = array();
      foreach ($this->contacts as $keyCompte => $compte) {
          $contact = CompteClient::getInstance()->find($keyCompte);
          if (!$contact) {
              $toberemoved[] = $keyCompte;
              continue;
          }
          $contact->setStatut($newStatus);
          $contact->save();
      }
      foreach($toberemoved as $keyCompte) {
          $this->removeContact($keyCompte);
      }
      $compte = $this->getMasterCompte();
      $compte->setStatut($newStatus);
      $etablissementtobesaved = array();
      foreach ($this->etablissements as $keyEtablissement => $etablissement) {
          $etablissement = EtablissementClient::getInstance()->find($keyEtablissement);
          $etablissement->setStatut($newStatus);
          $this->addCompte($etablissement->getMasterCompte());
          $etablissementtobesaved[] = $etablissement;
      }
      $this->setStatut($newStatus);
      $this->save();
      foreach($etablissementtobesaved as $etablissement) {
          $etablissement->save();
      }
    }

    /***
      Fonctions InterfaceMandatSepaPartie
    */

    public function getMandatSepaIdentifiant() {
      return $this->getIdentifiant();
    }
    public function getMandatSepaNom() {
      return $this->raison_sociale;
    }
    public function getMandatSepaAdresse() {
      return $this->siege->adresse;
    }
    public function getMandatSepaCodePostal() {
      return $this->siege->code_postal;
    }
    public function getMandatSepaCommune() {
      return $this->siege->commune;
    }
    // fin

    public function hasMandatSepa() {
      return (MandatSepaClient::getInstance()->findLastBySociete($this->getIdentifiant()) != null);
    }

    public function hasMandatSepaActif() {
      if (!MandatSepaConfiguration::getInstance()->isActive()) {
          return false;
      }
      $mandat = MandatSepaClient::getInstance()->findLastBySociete($this->getIdentifiant());
      if (!$mandat) {
          return false;
      }
      return $mandat->is_signe;
    }

    /*** TODO : Fonctions à retirer après le merge ****/


    public function getMasterCompte() {
        if (!$this->compte_societe) {

            return null;
        }
        return $this->getCompte($this->compte_societe);
    }

    public function getSiret() {
        return Anonymization::hideIfNeeded($this->_get('siret'));
    }

    public function getRaisonSociale() {
        return Anonymization::hideIfNeeded($this->_get('raison_sociale'));
    }

}
