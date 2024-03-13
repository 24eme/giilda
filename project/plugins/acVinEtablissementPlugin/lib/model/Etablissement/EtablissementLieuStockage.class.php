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
      if ($this->_get('nom')) {
        return $this->_get('nom');
      }
      return $this->getDocument()->nom;
    }
    public function getAdresse() {
      if ($this->_get('adresse')) {
        return $this->_get('adresse');
      }
      return $this->getDocument()->adresse;
    }
    public function getCommune() {
      if ($this->_get('commune')) {
        return $this->_get('commune');
      }
      return $this->getDocument()->commune;
    }
    public function getCodePostal() {
      if ($this->_get('code_postal')) {
        return $this->_get('code_postal');
      }
      return $this->getDocument()->code_postal;
    }

}
