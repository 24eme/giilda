<?php
class MandatSepaConfiguration implements InterfaceMandatSepaPartie {

  protected static $_instance;
  protected $configuration;


  public static function getInstance() {
      if ( ! isset(self::$_instance)) {
          self::$_instance = new self();
      }
      return self::$_instance;
  }
  public function __construct() {
      if(!sfConfig::has('mandatsepa_configuration')) {
		      throw new sfException("La configuration pour les mandats SEPA n'a pas été définie pour cette application");
	    }
      $this->configuration = sfConfig::get('mandatsepa_configuration', array());
      $this->organisme = Organisme::getInstance();

  }

  public function isActive() {
      if(!isset($this->configuration['is_active'])){
        return false;
      }
      return $this->configuration['is_active'];
  }

  public function getFrequencePrelevement() {
      if(!isset($this->configuration['frequence_prelevement'])){
        return "RECURRENT";
      }
      return $this->configuration['frequence_prelevement'];
  }

  public function getMentionAutorisation() {
      if(!isset($this->configuration['mention_autorisation'])){
        return "En signant ce formulaire de mandat, vous autorisez (A) le Syndicat des Vins IGP à envoyer des instructions à votre banque pour débiter votre compte, et (B) votre banque à débiter votre compte conformément aux instructions du Syndicat des Vins IGP.";
      }
      return $this->configuration['mention_autorisation'];
  }

  public function getMentionRemboursement() {
      if(!isset($this->configuration['mention_remboursement'])){
        return "Vous bénéficiez d'un droit à remboursement par votre banque selon les conditions décrites dans la convention que vous avez passée avec elle. Toute demande de remboursement doit être présentée dans les 8 semaines suivant la date de débit de votre compte ou sans tarder et au plus tard dans les 13 mois en cas de prélèvement non autorisé.";
      }
      return $this->configuration['mention_remboursement'];
  }

  public function getMentionDroits() {
      if(!isset($this->configuration['mention_droits'])){
        return "Vos droits concernant le présent mandat sont expliqués dans un document que vous pouvez obtenir auprès de votre banque.";
      }
      return $this->configuration['mention_droits'];
  }

  public function getMandatSepaIdentifiant() {
      if ($this->organisme) {
          $c = $this->organisme->getCreditorId();
          if ($c) {
              return $c;
          }
      }
      if(!isset($this->configuration['creancier'])){
        return "";
      }
      if(!isset($this->configuration['creancier']['identifiant_ics'])){
        return "";
      }
      return $this->configuration['creancier']['identifiant_ics'];
  }

  public function getMandatSepaNom() {
      if ($this->organisme) {
          $nom = $this->organisme->getNom();
          if ($nom) {
              return $nom;
          }
      }
      if(!isset($this->configuration['creancier'])){
        return "";
      }
      if(!isset($this->configuration['creancier']['nom'])){
        return "";
      }
      return $this->configuration['creancier']['nom'];
  }
  public function getMandatSepaAdresse() {
      if ($this->organisme) {
          $a = $this->organisme->getAdresse();
          if ($a) {
              return $a;
          }
      }
      if(!isset($this->configuration['creancier'])){
        return "";
      }
      if(!isset($this->configuration['creancier']['adresse'])){
        return "";
      }
      return $this->configuration['creancier']['adresse'];
  }
  public function getMandatSepaCodePostal() {
      if ($this->organisme) {
          $cp = $this->organisme->getCodePostal();
          if ($cp) {
              return $cp;
          }
      }
      if(!isset($this->configuration['creancier'])){
        return "";
      }
      if(!isset($this->configuration['creancier']['code_postal'])){
        return "";
      }
      return $this->configuration['creancier']['code_postal'];
  }
  public function getMandatSepaCommune() {
      if ($this->organisme) {
          $c = $this->organisme->getCommune();
          if ($c) {
              return $c;
          }
      }
      if(!isset($this->configuration['creancier'])){
        return "";
      }
      if(!isset($this->configuration['creancier']['commune'])){
        return "";
      }
      return $this->configuration['creancier']['commune'];
  }
}
