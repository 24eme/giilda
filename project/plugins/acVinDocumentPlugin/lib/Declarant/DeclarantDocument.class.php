<?php

class DeclarantDocument
{
    protected $document;

    public function __construct(acCouchdbDocument $document)
    {
        $this->document = $document;
    }
    
    public function getIdentifiant()
    {
        return $this->document->identifiant;
    }

    public function getDeclarant()
    {
        return $this->document->declarant;
    }
    
    public function getEtablissementObject() {
        return EtablissementClient::getInstance()->findByIdentifiant($this->getIdentifiant());
    }

    public function storeDeclarant()
    {
        $etb = $this->getEtablissementObject();
        $declarant = $this->getDeclarant();
        $declarant->nom = $etb->nom;
        $declarant->cvi = $etb->cvi;
        $declarant->num_accise = $etb->no_accises;
        $declarant->num_tva_intracomm = $etb->no_tva_intracommunautaire;
        $declarant->adresse = $etb->siege->adresse;        
        $declarant->commune = $etb->siege->commune;
        $declarant->code_postal = $etb->siege->code_postal;
        $declarant->num_chai = 'NUM CHAI 0000';
        $declarant->raison_sociale = $etb->raison_sociale;
    }
}