<?php
/**
 * Model for EtablissementLieuStockage
 *
 */

class EtablissementLieuStockage extends BaseEtablissementLieuStockage {
    public function getNumeroIncremental() {
        if(!preg_match("/^(C?[0-9]{10})([0-9]{3})$/", $this->numero, $matches)) {
             throw new sfException("Numéro de stockage mal formé : ".$this->numero);
        }

        return $matches[2];
    }

    public function getIdentifiant() {
        if(!preg_match("/^(C?[0-9]{10})([0-9]{3})$/", $this->numero, $matches)) {
             throw new sfException("Numéro de stockage mal formé : ".$this->numero);
        }

        return $matches[1];
    }

    public function isPrincipale() {

        return $this->getDocument()->getLieuStockagePrincipal(false, $this->getIdentifiant())->getNumeroIncremental() == $this->getNumeroIncremental();
    }

    public function getNom() {
      if ($this->nom) {
        return $this->nom;
      }
      return $this->getDocument()->nom;
    }
    public function getAdresse() {
      if ($this->adresse) {
        return $this->adresse;
      }
      return $this->getDocument()->adresse;
    }
    public function getCommune() {
      if ($this->commune) {
        return $this->commune;
      }
      return $this->getDocument()->commune;
    }
    public function getCodePostal() {
      if ($this->code_postal) {
        return $this->code_postal;
      }
      return $this->getDocument()->code_postal;
    }

}
