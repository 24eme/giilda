<?php
class MandatSepa extends BaseMandatSepa {

  public function constructId() {
      $id = 'MANDATSEPA-' . $this->debiteur->identifiant_rum . '-' . str_replace('-', '', $this->date);
      $this->set('_id', $id);
  }

  public function setDebiteur($debiteur) {
    if (!$debiteur) {
      throw new Exception('Il faut definir un débiteur pour le mandat SEPA.');
    }
    $this->debiteur->setPartieInformations($debiteur);
  }

  public function setCreancier($creancier) {
    if (!$creancier) {
      throw new Exception('Il faut definir un creancier pour le mandat SEPA.');
    }
    $this->creancier->setPartieInformations($creancier);
  }

  public function getStatut() {
    if (!$this->is_signe) {
      return MandatSepaClient::STATUT_NONVALIDE;
    }
    if (!$this->is_actif) {
      return MandatSepaClient::STATUT_SIGNE;
    }
    return MandatSepaClient::STATUT_VALIDE;
  }

  public function switchIsSigne() {
    if ($this->is_signe) {
      $this->is_signe = 0;
      $this->is_actif = 0;
    } else {
      $this->is_signe = 1;
      $this->is_actif = 1;
    }
  }

  public function switchIsActif() {
    if ($this->is_actif) {
      $this->is_actif = 0;
    } else {
      $this->is_actif = 1;
    }
  }

  public function getReference($withRev = true) {
    $ref = $this->debiteur->identifiant_rum . '-' . str_replace('-', '', $this->date);
    if ($withRev) {
      $ref .= '/'.$this->_rev;
    }
    return $ref;
  }

  public function getDateFr() {
    if (!$this->date) {
      return '';
    }
    $d = new DateTime($this->date);
    return $d->format('d/m/Y');
  }

  public function getIban(){
    if (!$this->debiteur->iban) {
      return '';
    }
    return $this->debiteur->iban;
  }
  
  public function getIbanFormate() {
    if (!$this->debiteur->iban) {
      return '';
    }
    $iban = $this->debiteur->iban;
    $result = '';
    $length = strlen($this->debiteur->iban);
    for($i=0; $i<$length; $i++) {
      if ($result && ($i % 4 === 0)) {
        $result .= ' ';
      }
      $result .= $iban[$i];
    }
    return $result;
  }

  public function getNumeroRum(){
    if(!$this->debiteur->identifiant_rum){
      return '';
    }
    return $this->debiteur->identifiant_rum;
  }

  public function getBic(){
    if(!$this->debiteur->bic){
      return '';
    }
    return $this->debiteur->bic;
  }
}
