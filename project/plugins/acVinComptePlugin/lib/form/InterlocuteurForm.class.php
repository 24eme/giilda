<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InterlocuteurForm
 *
 * @author mathurin
 */
class InterlocuteurForm extends CompteGeneriqueForm {

    private $compte;
    private $societeCompte;
    private $isEtablissement = false;

    
    public function __construct(Compte $compte, $options = array(), $CSRFSecret = null) {
        $this->compte = $compte;
        $this->societeCompte = $this->compte->getSociete()->getMasterCompte();
        $this->isEtablissement = array_key_exists('etablissement',$options) && $options['etablissement'];
               
        parent::__construct($compte, $options, $CSRFSecret);
        if ($this->compte->isSocieteContact() && !$this->isEtablissement) {
            $this->defaults['pays'] = 'FR';
        }        
    }

    public function configure() {
        parent::configure();
        $this->setWidget('civilite', new bsWidgetFormChoice(array('choices' => CompteGeneriqueForm::getCiviliteList())));
        $this->setWidget('nom', new bsWidgetFormInput());
        $this->setWidget('prenom', new bsWidgetFormInput());
        $this->setWidget('fonction', new bsWidgetFormInput());
        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->widgetSchema->setLabel('civilite', 'Civilite *');
        $this->widgetSchema->setLabel('nom', 'Nom *');
        $this->widgetSchema->setLabel('prenom', 'Prenom');
        $this->widgetSchema->setLabel('fonction', 'Fonction *');
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');
        

        $this->setValidator('civilite', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(CompteGeneriqueForm::getCiviliteList()))));
        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('prenom', new sfValidatorString(array('required' => false)));
        $this->setValidator('fonction', new sfValidatorString(array('required' => false)));
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
           
        $this->widgetSchema->setNameFormat('compte_modification[%s]');
    }
  

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
         
    }
    
    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

       
       /* 
        if ($this->compte->isSameAdresseThanSociete()) {
            if ($this->compte->adresse == $this->societeCompte->adresse) {
                $this->setDefault('adresse', '');
            }
            
            if ($this->compte->adresse_complementaire == $this->societeCompte->adresse_complementaire) {
                $this->setDefault('adresse_complementaire', '');
            }
            if ($this->compte->code_postal == $this->societeCompte->code_postal) {
                $this->setDefault('code_postal', '');
            }

            if ($this->compte->commune == $this->societeCompte->commune) {
                $this->setDefault('commune', '');
            }

            if ($this->compte->cedex == $this->societeCompte->cedex) {
                $this->setDefault('cedex', '');
            }
            if ($this->compte->pays == $this->societeCompte->pays) {
                $this->setDefault('pays', '');
            }
        }
        
        if ($this->compte->isSameContactThanSociete()) {
            if ($this->compte->telephone_bureau == $this->societeCompte->telephone_bureau) {
                $this->setDefault('telephone_bureau', '');
            }
            
            if ($this->compte->telephone_mobile == $this->societeCompte->telephone_mobile) {
                $this->setDefault('telephone_mobile', '');
            }
            if ($this->compte->telephone_perso == $this->societeCompte->telephone_perso) {
                $this->setDefault('telephone_perso', '');
            }

            if ($this->compte->email == $this->societeCompte->email) {
                $this->setDefault('email', '');
            }

            if ($this->compte->fax == $this->societeCompte->fax) {
                $this->setDefault('fax', '');
            }
        }*/
    }
  
}
