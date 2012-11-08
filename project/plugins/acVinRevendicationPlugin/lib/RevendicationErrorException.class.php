<?php

class RevendicationErrorException extends Exception 
{
    const ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS = "ETABLISSEMENT";
    const ERREUR_TYPE_PRODUIT_NOT_EXISTS = "PRODUIT";
    const ERREUR_TYPE_BAILLEUR_NOT_EXISTS = "BAILLEUR";
    const ERREUR_TYPE_DOUBLON = "DOUBLON";
    
    const ERREUR_TYPE_DOUBLON_LIBELLE = "L'etablissement de cvi %s a déjà possède déjà un volume revendiqué pour le produit %s (%s hl).";
    const ERREUR_TYPE_ETABLISSEMENT_NOT_EXISTS_LIBELLE = "L'etablissement de cvi %s n'existe pas.";
    const ERREUR_TYPE_PRODUIT_NOT_EXISTS_LIBELLE = "Le produit %s n'existe pas.";
    const ERREUR_TYPE_BAILLEUR_NOT_EXISTS_LIBELLE = "Le bailleur %s n'existe pas.";
    
    protected $exception_type;


    public function __construct($exception_type, $message = null, $code = null, $previous = null)
   {
       $this->exception_type = $exception_type;
       parent::__construct($message, $code, $previous);
   }
   
   public function getErrorType() {
       return $this->exception_type;
   }
   
   public function getErrorMessage() {
       return $this->exception_type;
   }
}