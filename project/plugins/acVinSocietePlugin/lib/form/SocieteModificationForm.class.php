<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteModificationForm
 * @author mathurin
 */
class SocieteModificationForm extends acCouchdbObjectForm {

    private $types_societe = null;
    private $types_numero_compte = null;
    private $statuts = null;

    public function __construct(Societe $societe, $options = array(), $CSRFSecret = null) {
        parent::__construct($societe, $options, $CSRFSecret);
        $this->setSocieteTypes();
        $this->setStatuts();
        $this->setTypesNumeroCompte();
    }

    public function configure() {
        $this->setWidget('raison_sociale', new sfWidgetFormInput());
        $this->setWidget('raison_sociale_abregee', new sfWidgetFormInput());
        $this->setWidget('statut', new sfWidgetFormChoice(array('choices' => $this->getStatuts(), 'multiple' => false,'expanded' => true)));
        
      //  $this->setWidget('type_societe', new sfWidgetFormChoice(array('choices' => $this->getSocieteTypes())));
        $this->setWidget('type_numero_compte', new sfWidgetFormChoice(array('choices' => $this->getTypesNumeroCompte(),'multiple' => true,'expanded' => true)));
        $this->setWidget('cooperative', new sfWidgetFormChoice(array('choices' => $this->getCooperative(),'multiple' => false,'expanded' => true)));
        
        $this->setWidget('siret', new sfWidgetFormInput());
        $this->setWidget('code_naf', new sfWidgetFormInput());
        $this->setWidget('tva_intracom', new sfWidgetFormInput());
        foreach ($this->getObject()->enseignes as $key => $enseigne) {
            $this->setWidget('enseignes[' . $key . ']', new sfWidgetFormInput());
        }
        $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));


        $this->widgetSchema->setLabel('raison_sociale', 'Nom de la société');
        $this->widgetSchema->setLabel('raison_sociale_abregee', 'Abrégé');
        $this->widgetSchema->setLabel('statut', 'Statut');
       // $this->widgetSchema->setLabel('type_societe', 'Type de société');
        $this->widgetSchema->setLabel('type_numero_compte', 'Numéro de compte');
        $this->widgetSchema->setLabel('cooperative', 'Société coopérative');
        $this->widgetSchema->setLabel('siret', 'SIRET');
        $this->widgetSchema->setLabel('code_naf', 'Code Naf');
        $this->widgetSchema->setLabel('tva_intracom', 'TVA Intracom');
        foreach ($this->getObject()->enseignes as $key => $enseigne) {
            $this->widgetSchema->setLabel('enseignes[' . $key . ']', 'Enseigne');
        }
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');


        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('raison_sociale_abregee', new sfValidatorString(array('required' => false)));
        $this->setValidator('statut', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getStatuts()))));
       // $this->setValidator('type_societe', new sfValidatorChoice(array('required' => true, 'choices' => $this->getSocieteTypesValid())));
        $this->setValidator('type_numero_compte', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesNumeroCompte()), 'multiple' => true)));
        $this->setValidator('cooperative', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCooperative()))));
        
        $this->setValidator('siret', new sfValidatorString(array('required' => false)));
        $this->setValidator('code_naf', new sfValidatorString(array('required' => false)));
        $this->setValidator('tva_intracom', new sfValidatorString(array('required' => false)));
        foreach ($this->getObject()->enseignes as $key => $enseigne) {
            $this->setValidator('enseignes[' . $key . ']', new sfValidatorString(array('required' => false)));
        }
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setNameFormat('societe_modification[%s]');
    }

    public function getCooperative() {
        return array('Non', 'Oui');
    }
    
    public function getStatuts() {
        if (!$this->statuts)
            $this->setStatuts();
        return $this->statuts;
    }

    public function getSocieteTypes() {
        if (!$this->types_societe)
            $this->setSocieteTypes();
        return $this->types_societe;
    }
    
    public function getTypesNumeroCompte() {
        if (!$this->types_numero_compte)
            $this->setTypesNumeroCompte();
        return $this->types_numero_compte;
    }
    
    private function setSocieteTypes() {
        $this->types_societe = SocieteClient::getSocieteTypes();
    }

    private function setTypesNumeroCompte() {
        $this->types_numero_compte = SocieteClient::getTypesNumeroCompte();
    }

    private function setStatuts() {
        $this->statuts = SocieteClient::getStatuts();
    }

    private function getSocieteTypesValid() {
        $societeTypes = SocieteClient::getSocieteTypes();
        $result = array();
        foreach ($societeTypes as $types) {
            if (!is_array($types))
                $result[] = $types;
            else {
                foreach ($types as $entree) {
                    $result[] = $entree;
                }
            }
        }
        return $result;
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }
}

?>
