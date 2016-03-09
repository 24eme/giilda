<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteModificationForm
 * @author mathurin
 */
class SocieteModificationForm extends CompteGeneriqueForm {

    private $types_societe = null;
    private $statuts = null;
    private $isOperateur = false;

    public function __construct(Societe $societe, $options = array(), $CSRFSecret = null) {
        parent::__construct($societe, $options, $CSRFSecret);
        $this->isOperateur = $societe->canHaveChais();
        $this->setSocieteTypes();
    }

    public function configure() {

        parent::configure();

        $this->setWidget('raison_sociale', new bsWidgetFormInput());
        $this->setWidget('raison_sociale_abregee', new bsWidgetFormInput());
        if (false) {
            $this->setWidget('type_numero_compte_fournisseur', new bsWidgetFormChoice(array('choices' => $this->getTypesNumeroCompteFournisseur(), 'multiple' => true, 'expanded' => true)));
            $this->widgetSchema->setLabel('type_numero_compte_fournisseur', 'Numéros de compte');
        }
        if ($this->getObject()->isNegoOrViti()) {
            //$this->setWidget('type_numero_compte_client', new bsWidgetFormChoice(array('choices' => $this->getTypesNumeroCompteClient(), 'multiple' => true, 'expanded' => true)));

            $this->setWidget('cooperative', new bsWidgetFormChoice(array('choices' => $this->getCooperative(), 'multiple' => false, 'expanded' => true)));
        }

        $this->setWidget('type_fournisseur', new bsWidgetFormChoice(array('choices' => $this->getTypesFournisseur(), 'multiple' => true, 'expanded' => true)));

        $this->setWidget('siret', new bsWidgetFormInput());
        $this->setWidget('code_naf', new bsWidgetFormInput());
        $this->setWidget('no_tva_intracommunautaire', new bsWidgetFormInput());

        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));


        $this->widgetSchema->setLabel('raison_sociale', 'Nom de la société *');
        $this->widgetSchema->setLabel('raison_sociale_abregee', 'Abrégé');

        if ($this->getObject()->isNegoOrViti()) {
            $this->widgetSchema->setLabel('cooperative', 'Cave coopérative *');
        }

        $this->widgetSchema->setLabel('type_fournisseur', 'Type fournisseur');


        $this->widgetSchema->setLabel('siret', 'SIRET');
        $this->widgetSchema->setLabel('code_naf', 'Code Naf');
        $this->widgetSchema->setLabel('no_tva_intracommunautaire', 'TVA Intracom.');

        $this->widgetSchema->setLabel('commentaire', 'Commentaire');


        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('raison_sociale_abregee', new sfValidatorString(array('required' => false)));
       
        $this->setValidator('type_numero_compte_fournisseur', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesNumeroCompteFournisseur()), 'multiple' => true)));

        if ($this->getObject()->isNegoOrViti()) {
            $this->setValidator('cooperative', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCooperative()))));
            //$this->setValidator('type_numero_compte_client', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesNumeroCompteClient()), 'multiple' => true)));
        }

        $this->setValidator('type_fournisseur', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesFournisseur()), 'multiple' => true)));


        $this->setValidator('siret', new sfValidatorString(array('required' => false)));
        $this->setValidator('code_naf', new sfValidatorString(array('required' => false)));
        $this->setValidator('no_tva_intracommunautaire', new sfValidatorString(array('required' => false)));
        if ($this->getObject()->code_comptable_client) {
            //$this->widgetSchema['type_numero_compte_client']->setAttribute('disabled', 'disabled');
        }

        if ($this->getObject()->code_comptable_fournisseur) {
            $this->widgetSchema['type_numero_compte_fournisseur']->setAttribute('disabled', 'disabled');
        } else {
            $this->widgetSchema['type_fournisseur']->setAttribute('disabled', 'disabled');
        }      

        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));


        $this->widgetSchema->setNameFormat('societe_modification[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();      

        $this->setDefault('type_fournisseur', $this->getDefaultTypesFournisseur());
  

        if ($this->getObject()->isNegoOrViti()) {
            if (is_null($this->getObject()->cooperative)) {
                $this->setDefault('cooperative', 0);
            }
            $this->setDefault('type_numero_compte_client', $this->getDefaultNumeroCompteClient());
        }

        $this->setDefault('type_numero_compte_fournisseur', $this->getDefaultNumeroCompteFournisseur());
    }

    protected function getDefaultTypesFournisseur() {
        $types_fournisseur = array();
        if ($this->getObject()->exist('type_fournisseur')) {
            foreach ($this->getObject()->type_fournisseur as $type_fournisseur) {
                $types_fournisseur[$type_fournisseur] = $type_fournisseur;
            }
        }
        return $types_fournisseur;
    }

    protected function getDefaultNumeroCompteClient() {
        $type_numero_compte_client = array();
        if (($this->getObject()->code_comptable_client) || $this->getObject()->isNegoOrViti() && !$this->getObject()->siret) {
            $type_numero_compte_client[SocieteClient::NUMEROCOMPTE_TYPE_CLIENT] = SocieteClient::NUMEROCOMPTE_TYPE_CLIENT;
        }
        return $type_numero_compte_client;
    }

    protected function getDefaultNumeroCompteFournisseur() {
        $type_numero_compte_fournisseur = array();
        if ($this->getObject()->code_comptable_fournisseur) {
            $type_numero_compte_fournisseur[SocieteClient::NUMEROCOMPTE_TYPE_FOURNISSEUR] = SocieteClient::NUMEROCOMPTE_TYPE_FOURNISSEUR;
        }
        return $type_numero_compte_fournisseur;
    }

    public function getIsOperateur() {
        return $this->isOperateur;
    }

    public function getCooperative() {
        return array('Non', 'Oui');
    }
   

    public function getSocieteTypes() {
        if (!$this->types_societe) {
            $this->setSocieteTypes();
        }
        return $this->types_societe;
    }

    public function getTypesNumeroCompteClient() {
        return array(SocieteClient::NUMEROCOMPTE_TYPE_CLIENT => 'Client');
    }

    public function getTypesNumeroCompteFournisseur() {
        return array(SocieteClient::NUMEROCOMPTE_TYPE_FOURNISSEUR => 'Fournisseur');
    }

    public function getTypesFournisseur() {
        return array();
    }

    private function setSocieteTypes() {
        $this->types_societe = SocieteClient::getSocieteTypes();
    }

   
    public function update() {      
        if (($this->getObject()->code_comptable_client) || ($this->getObject()->isNegoOrViti() && $this->values['type_numero_compte_client'])) {
            $this->getObject()->code_comptable_client = preg_replace('/^0*/', '', $this->getObject()->identifiant);
        } else
            $this->getObject()->code_comptable_client = null;

        if ($this->values['type_numero_compte_fournisseur']) {
            $this->getObject()->code_comptable_fournisseur = SocieteClient::getInstance()->getNextCodeFournisseur();
        }

        if (!$this->getObject()->isNegoOrViti() && ($this->getObject()->code_comptable_fournisseur)) {
            if ($this->values['type_fournisseur'])
                $this->getObject()->add('type_fournisseur', $this->values['type_fournisseur']);
            else
                $this->getObject()->add('type_fournisseur', array());
        }
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }
    

}

?>
