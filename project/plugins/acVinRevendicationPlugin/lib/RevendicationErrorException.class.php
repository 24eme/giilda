<?php

class RevendicationErrorException extends Exception {

    const ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS = "ETABLISSEMENT";
    const ERREUR_TYPE_PRODUIT_NOT_EXISTS = "PRODUIT";
    const ERREUR_TYPE_BAILLEUR_NOT_EXISTS = "BAILLEUR";
    const ERREUR_TYPE_NO_BAILLEURS = "NOBAILLEUR";
    const ERREUR_TYPE_DOUBLON = "DOUBLON";
    const ERREUR_TYPE_DATE_CAMPAGNE = "CAMPAGNE";
    
    const ERREUR_TYPE_DOUBLON_LIBELLE = "L'etablissement de cvi %s possède déjà un volume revendiqué pour le produit %s (%s hl).";
    const ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS_LIBELLE = "L'etablissement de cvi %s n'existe pas.";
    const ERREUR_TYPE_PRODUIT_NOT_EXISTS_LIBELLE = "Le produit %s (%s) n'existe pas.";
    const ERREUR_TYPE_BAILLEUR_NOT_EXISTS_LIBELLE = "L'etablissement %s ne possède pas le bailleur %s.";
    const ERREUR_TYPE_NO_BAILLEURS_LIBELLE = "L'etablissement %s ne possède aucun bailleurs, le bailleur %s ne peut être reconnu.";
    const ERREUR_TYPE_DATE_CAMPAGNE_LIBELLE = "La campagne %s ne correspond pas à la campagne en cours de saisie.";
    
    protected $exception_type;
    protected $arguments;

    public function __construct($exception_type, $arguments = array(), $message = null, $code = null, $previous = null) {
        $this->exception_type = $exception_type;
        $this->arguments = $arguments;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorType() {
        return $this->exception_type;
    }

    public function getArguments() {
        return $this->arguments;
    }

    public function getErrorMessage() {
        return $this->exception_type;
    }

    public function addArgument($k, $v)  {
      $this->arguments[$k] = $v;
    }

    public static function getLibellesForErrorsType() {
        return array(self::ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS => "CVI non reconnus",
            self::ERREUR_TYPE_PRODUIT_NOT_EXISTS => "Produits non reconnus",
            self::ERREUR_TYPE_BAILLEUR_NOT_EXISTS => "Bailleur non reconnus",
            self::ERREUR_TYPE_DOUBLON => "Doublons",
            self::ERREUR_TYPE_NO_BAILLEURS => "Aucun bailleur",
            self::ERREUR_TYPE_DATE_CAMPAGNE => "Mauvaise campagne");
    }
    
}