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
    private $isOperateur = false;
    private $enseignes = null;

    public function __construct(Societe $societe, $options = array(), $CSRFSecret = null) {
        $this->isOperateur = $societe->canHaveChais();
        $this->setSocieteTypes();
        $this->setStatuts();
        $this->enseignes = $societe->enseignes;
        parent::__construct($societe, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('raison_sociale', new sfWidgetFormInput());
        $this->setWidget('raison_sociale_abregee', new sfWidgetFormInput());
        $this->setWidget('statut', new sfWidgetFormChoice(array('choices' => $this->getStatuts(), 'multiple' => false, 'expanded' => true)));

        //  $this->setWidget('type_societe', new sfWidgetFormChoice(array('choices' => $this->getSocieteTypes())));
        $this->setWidget('type_numero_compte', new sfWidgetFormChoice(array('choices' => $this->getTypesNumeroCompte(), 'multiple' => true, 'expanded' => true)));
        if ($this->getObject()->isNegoOrViti()) {
            $this->setWidget('cooperative', new sfWidgetFormChoice(array('choices' => $this->getCooperative(), 'multiple' => false, 'expanded' => true)));
        }

        $this->setWidget('siret', new sfWidgetFormInput());
        $this->setWidget('code_naf', new sfWidgetFormInput());
        $this->setWidget('no_tva_intracommunautaire', new sfWidgetFormInput());

        $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->embedForm('enseignes', new EnseignesItemForm($this->getObject()->enseignes));

        $this->widgetSchema->setLabel('raison_sociale', 'Nom de la société *');
        $this->widgetSchema->setLabel('raison_sociale_abregee', 'Abrégé');
        $this->widgetSchema->setLabel('statut', 'Statut');
        // $this->widgetSchema->setLabel('type_societe', 'Type de société');
        $this->widgetSchema->setLabel('type_numero_compte', 'Numéros de compte');

        if ($this->getObject()->isNegoOrViti()) {
            $this->widgetSchema->setLabel('cooperative', 'Cave coopérative *');
        }

        $this->widgetSchema->setLabel('siret', 'SIRET');
        $this->widgetSchema->setLabel('code_naf', 'Code Naf');
        $this->widgetSchema->setLabel('no_tva_intracommunautaire', 'TVA Intracom.');
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');


        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('raison_sociale_abregee', new sfValidatorString(array('required' => false)));
        $this->setValidator('statut', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));
        // $this->setValidator('type_societe', new sfValidatorChoice(array('required' => true, 'choices' => $this->getSocieteTypesValid())));

        $this->setValidator('type_numero_compte', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesNumeroCompte()), 'multiple' => true)));

        if ($this->getObject()->isNegoOrViti()) {
            $this->setValidator('cooperative', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCooperative()))));
        }
        
        $this->setValidator('siret', new sfValidatorString(array('required' => false)));
        $this->setValidator('code_naf', new sfValidatorString(array('required' => false)));
        $this->setValidator('no_tva_intracommunautaire', new sfValidatorString(array('required' => false)));
        
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
        
        if($this->getObject()->hasNumeroCompte()) {
                $this->widgetSchema['type_numero_compte']->setAttribute('disabled', 'disabled');
        }
    
        if($this->getObject()->isInCreation()){
            $this->widgetSchema['statut']->setAttribute('disabled', 'disabled');
        }
        
        $this->widgetSchema->setNameFormat('societe_modification[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

        if($this->getObject()->isInCreation()) {
            $this->setDefault('statut', SocieteClient::STATUT_ACTIF);
        }
        
        if ($this->getObject()->isNegoOrViti() && is_null($this->getObject()->cooperative)) {
            $this->setDefault('cooperative', 0);
        }
        
        $this->setDefault('type_numero_compte', $this->getDefaultNumeroCompte());
    }

    protected function getDefaultNumeroCompte() {
        $type_numero_compte = array();

        if($this->getObject()->code_comptable_client) {
            $type_numero_compte[SocieteClient::NUMEROCOMPTE_TYPE_CLIENT] = SocieteClient::NUMEROCOMPTE_TYPE_CLIENT;
        }
        if($this->getObject()->code_comptable_fournisseur) {
            $type_numero_compte[SocieteClient::NUMEROCOMPTE_TYPE_FOURNISSEUR] = SocieteClient::NUMEROCOMPTE_TYPE_FOURNISSEUR;
        }
        if($this->getObject()->isNegoOrViti() && !$this->getObject()->siret)  {
            $type_numero_compte[SocieteClient::NUMEROCOMPTE_TYPE_CLIENT] = SocieteClient::NUMEROCOMPTE_TYPE_CLIENT;
        }

        return $type_numero_compte;
    }

    public function getIsOperateur() {
        return $this->isOperateur;
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
        if(!$this->getObject()->isNegoOrViti()) 
            unset ($this->types_numero_compte[SocieteClient::NUMEROCOMPTE_TYPE_CLIENT]);
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

    public function update() {
        foreach ($this->getEmbeddedForms() as $key => $form) {
            $form->updateObject($this->values[$key]);
        }
        if ($this->values['type_numero_compte']) {
            $this->getObject()->setCodesComptables($this->values['type_numero_compte']);
        }     
    }
    
     protected function doSave($con = null) {
        if (null === $con) {
            $con = $this->getConnection();
        }

        $this->updateObject();  
        if(!$this->getObject()->siege->commune){
            $this->getObject()->setStatut(SocieteClient::STATUT_ACTIF);
        }
        $this->object->getCouchdbDocument()->save();        
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if ($form instanceof EnseignesItemForm) {
                if (isset($taintedValues[$key])) {
                    $form->bind($taintedValues[$key], $taintedFiles[$key]);
                    $this->updateEmbedForm($key, $form);
                }
            }
        }

        if(!array_key_exists('type_numero_compte', $taintedValues)) {
            $taintedValues['type_numero_compte'] = $this->getDefaultNumeroCompte();
        }

        if(!array_key_exists('statut', $taintedValues)) {

            $taintedValues['statut'] = (!$this->getObject()->isInCreation()) ? $this->getObject()->statut : SocieteClient::STATUT_ACTIF;
        }

        parent::bind($taintedValues, $taintedFiles);
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    public function getFormTemplate() {
        $societe = new Societe();
        $form_embed = new EnseigneItemForm($societe->enseignes->add());
        $form = new SocieteCollectionTemplateForm($this, 'enseignes', $form_embed);
        return $form->getFormTemplate();
    }

    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->enseignes->remove($key);
    }

}

?>
