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
    private $statuts = null;
    private $isOperateur = false;
    private $enseignes = null;
    private $reduct_rights = false;

    public function __construct(Societe $societe, $reduct_rights = false, $options = array(), $CSRFSecret = null) {
        $this->reduct_rights = $reduct_rights;
        $this->isOperateur = $societe->canHaveChais();
        $this->setSocieteTypes();
        $this->setStatuts();
        $this->enseignes = $societe->enseignes;
        parent::__construct($societe, $options, $CSRFSecret);
    }

    public function configure() {
        if (!$this->reduct_rights) {
            $this->setWidget('raison_sociale', new bsWidgetFormInput());
            $this->setWidget('raison_sociale_abregee', new bsWidgetFormInput());
            $this->setWidget('statut', new bsWidgetFormChoice(array('choices' => $this->getStatuts(), 'multiple' => false, 'expanded' => true)));

            //  $this->setWidget('type_societe', new sfWidgetFormChoice(array('choices' => $this->getSocieteTypes())));
            if (false) {
	            $this->setWidget('type_numero_compte_fournisseur', new bsWidgetFormChoice(array('choices' => $this->getTypesNumeroCompteFournisseur(), 'multiple' => true, 'expanded' => true)));
                    $this->widgetSchema->setLabel('type_numero_compte_fournisseur', 'Numéros de compte');
	    }
            if ($this->getObject()->isNegoOrViti()) {
                $this->setWidget('type_numero_compte_client', new bsWidgetFormChoice(array('choices' => $this->getTypesNumeroCompteClient(), 'multiple' => true, 'expanded' => true)));

                $this->setWidget('cooperative', new bsWidgetFormChoice(array('choices' => $this->getCooperative(), 'multiple' => false, 'expanded' => true)));
            }

            $this->setWidget('type_fournisseur', new bsWidgetFormChoice(array('choices' => $this->getTypesFournisseur(), 'multiple' => true, 'expanded' => true)));

            $this->setWidget('siret', new bsWidgetFormInput());
            $this->setWidget('code_naf', new bsWidgetFormInput());
            $this->setWidget('no_tva_intracommunautaire', new bsWidgetFormInput());
            $this->embedForm('enseignes', new EnseignesItemForm($this->getObject()->enseignes));
        }
        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        if (!$this->reduct_rights) {
            $this->widgetSchema->setLabel('raison_sociale', 'Nom de la société *');
            $this->widgetSchema->setLabel('raison_sociale_abregee', 'Abrégé');
            $this->widgetSchema->setLabel('statut', 'Statut');
            // $this->widgetSchema->setLabel('type_societe', 'Type de société');

            if ($this->getObject()->isNegoOrViti()) {
                $this->widgetSchema->setLabel('cooperative', 'Cave coopérative *');
            }

            $this->widgetSchema->setLabel('type_fournisseur', 'Type fournisseur');


            $this->widgetSchema->setLabel('siret', 'SIRET');
            $this->widgetSchema->setLabel('code_naf', 'Code Naf');
            $this->widgetSchema->setLabel('no_tva_intracommunautaire', 'TVA Intracom.');
        }
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');

        if (!$this->reduct_rights) {
            $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
            $this->setValidator('raison_sociale_abregee', new sfValidatorString(array('required' => false)));
            $this->setValidator('statut', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));
            // $this->setValidator('type_societe', new sfValidatorChoice(array('required' => true, 'choices' => $this->getSocieteTypesValid())));

            $this->setValidator('type_numero_compte_fournisseur', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesNumeroCompteFournisseur()), 'multiple' => true)));

            if ($this->getObject()->isNegoOrViti()) {
                $this->setValidator('cooperative', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCooperative()))));
                $this->setValidator('type_numero_compte_client', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesNumeroCompteClient()), 'multiple' => true)));
            }

            $this->setValidator('type_fournisseur', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesFournisseur()), 'multiple' => true)));


            $this->setValidator('siret', new sfValidatorString(array('required' => false)));
            $this->setValidator('code_naf', new sfValidatorString(array('required' => false)));
            $this->setValidator('no_tva_intracommunautaire', new sfValidatorString(array('required' => false)));
            if ($this->getObject()->code_comptable_client) {
                $this->widgetSchema['type_numero_compte_client']->setAttribute('disabled', 'disabled');
            }

            if ($this->getObject()->code_comptable_fournisseur) {
                $this->widgetSchema['type_numero_compte_fournisseur']->setAttribute('disabled', 'disabled');
            } else {
                // if(!$this->getObject()->isNegoOrViti())
                $this->widgetSchema['type_fournisseur']->setAttribute('disabled', 'disabled');
            }

            if ($this->getObject()->isInCreation()) {
                $this->widgetSchema['statut']->setAttribute('disabled', 'disabled');
            }
        }
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));


        $this->widgetSchema->setNameFormat('societe_modification[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();

        if (!$this->reduct_rights) {
            if ($this->getObject()->isInCreation()) {
                $this->setDefault('statut', SocieteClient::STATUT_ACTIF);
            }

            // if (!$this->getObject()->isNegoOrViti()){             
            $this->setDefault('type_fournisseur', $this->getDefaultTypesFournisseur());
            // }

            if ($this->getObject()->isNegoOrViti()) {
                if (is_null($this->getObject()->cooperative)) {
                    $this->setDefault('cooperative', 0);
                }
                $this->setDefault('type_numero_compte_client', $this->getDefaultNumeroCompteClient());
            }

            $this->setDefault('type_numero_compte_fournisseur', $this->getDefaultNumeroCompteFournisseur());
        }
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

    public function getStatuts() {
        if (!$this->statuts) {
            $this->setStatuts();
        }
        return $this->statuts;
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
        if (!$this->reduct_rights) {
            foreach ($this->getEmbeddedForms() as $key => $form) {
                $form->updateObject($this->values[$key]);
            }
            if (($this->getObject()->code_comptable_client) || ($this->getObject()->isNegoOrViti() && $this->values['type_numero_compte_client'])) {

                if ($this->getObject()->type_societe == SocieteClient::SUB_TYPE_VITICULTEUR)
                    $this->getObject()->code_comptable_client = '02' . $this->getObject()->identifiant;
                if ($this->getObject()->type_societe == SocieteClient::SUB_TYPE_NEGOCIANT)
                    $this->getObject()->code_comptable_client = '04' . $this->getObject()->identifiant;
            } else
                $this->getObject()->code_comptable_client = null;

            if ($this->values['type_numero_compte_fournisseur']) {
                $this->getObject()->code_comptable_fournisseur = SocieteClient::getInstance()->getNextCodeFournisseur();
            }

//        if($this->getObject()->isNegoOrViti() && $this->getObject()->code_comptable_fournisseur){
//            $this->getObject()->add('type_fournisseur',array(SocieteClient::FOURNISSEUR_TYPE_MDV));
//        }

            if (!$this->getObject()->isNegoOrViti() && ($this->getObject()->code_comptable_fournisseur)) {
                if ($this->values['type_fournisseur'])
                    $this->getObject()->add('type_fournisseur', $this->values['type_fournisseur']);
                else
                    $this->getObject()->add('type_fournisseur', array());
            }
        }
    }

    protected function doSave($con = null) {
        if (null === $con) {
            $con = $this->getConnection();
        }

        $this->updateObject();
        if ((!$this->reduct_rights) && !$this->getObject()->siege->commune) {
            $this->getObject()->setStatut(SocieteClient::STATUT_ACTIF);
        }
        $this->object->getCouchdbDocument()->save();
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        if (!$this->reduct_rights) {
            foreach ($this->embeddedForms as $key => $form) {
                if ($form instanceof EnseignesItemForm) {
                    if (isset($taintedValues[$key])) {
                        $form->bind($taintedValues[$key], $taintedFiles[$key]);
                        $this->updateEmbedForm($key, $form);
                    }
                }
            }
            if (!array_key_exists('statut', $taintedValues)) {

                $taintedValues['statut'] = (!$this->getObject()->isInCreation()) ? $this->getObject()->statut : SocieteClient::STATUT_ACTIF;
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
