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
        if ($this->isVitiOrNego()) {
            $this->setWidget('type_numero_compte', new sfWidgetFormChoice(array('choices' => $this->getTypesNumeroCompte(), 'multiple' => true, 'expanded' => true)));
            $this->setWidget('cooperative', new sfWidgetFormChoice(array('choices' => $this->getCooperative(), 'multiple' => false, 'expanded' => true)));
            $this->setWidget('siret', new sfWidgetFormInput());
            $this->setWidget('code_naf', new sfWidgetFormInput());
            $this->setWidget('tva_intracom', new sfWidgetFormInput());
        }
        if ($this->isCourtier()) {
            $this->setWidget('carte_professionnelle', new sfWidgetFormInput());
        }


        $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->embedForm('enseignes', new EnseignesItemForm($this->getObject()->enseignes));

        $this->widgetSchema->setLabel('raison_sociale', 'Nom de la société');
        $this->widgetSchema->setLabel('raison_sociale_abregee', 'Abrégé');
        $this->widgetSchema->setLabel('statut', 'Statut');
        // $this->widgetSchema->setLabel('type_societe', 'Type de société');
        if ($this->isVitiOrNego()) {
            $this->widgetSchema->setLabel('type_numero_compte', 'Numéros de compte');
            $this->widgetSchema->setLabel('cooperative', 'Cave coopérative');
            $this->widgetSchema->setLabel('siret', 'SIRET');
            $this->widgetSchema->setLabel('code_naf', 'Code Naf');
            $this->widgetSchema->setLabel('tva_intracom', 'TVA Intracom');
        }
        if ($this->isCourtier()) {
            $this->widgetSchema->setLabel('carte_professionnelle', 'Numéro de carte professionnelle');
        }


        $this->widgetSchema->setLabel('commentaire', 'Commentaire');


        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('raison_sociale_abregee', new sfValidatorString(array('required' => false)));
        $this->setValidator('statut', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getStatuts()))));
        // $this->setValidator('type_societe', new sfValidatorChoice(array('required' => true, 'choices' => $this->getSocieteTypesValid())));

        if ($this->isVitiOrNego()) {
            $this->setValidator('type_numero_compte', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesNumeroCompte()), 'multiple' => true)));
            $this->setValidator('cooperative', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCooperative()))));
            $this->setValidator('siret', new sfValidatorString(array('required' => false)));
            $this->setValidator('code_naf', new sfValidatorString(array('required' => false)));
            $this->setValidator('tva_intracom', new sfValidatorString(array('required' => false)));
        }
        if ($this->isCourtier()) {
            $this->setValidator('carte_professionnelle', new sfValidatorString(array('required' => false)));
        }
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setNameFormat('societe_modification[%s]');
    }

    public function isCourtier() {
        return $this->getObject()->type_societe == SocieteClient::SUB_TYPE_COURTIER;
    }

    public function isVitiOrNego() {
        return (($this->getObject()->type_societe == SocieteClient::SUB_TYPE_NEGOCIANT) || ($this->getObject()->type_societe == SocieteClient::SUB_TYPE_VITICULTEUR));
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
