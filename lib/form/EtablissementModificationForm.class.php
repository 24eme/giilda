<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteModificationForm
 * @author mathurin
 */
class EtablissementModificationForm extends acCouchdbObjectForm {

    private $etablissement;
    private $liaisons_operateurs = null;

    public function __construct(Etablissement $etablissement, $options = array(), $CSRFSecret = null) {
        $this->etablissement = $etablissement;
        $this->liaisons_operateurs = $etablissement->liaisons_operateurs;
        parent::__construct($etablissement, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('nom', new sfWidgetFormInput());
        $this->setWidget('statut', new sfWidgetFormChoice(array('choices' => $this->getStatuts(), 'multiple' => false, 'expanded' => true)));

        if (!$this->etablissement->isCourtier()) {
            $this->setWidget('cvi', new sfWidgetFormInput());
            $this->setWidget('relance_ds', new sfWidgetFormChoice(array('choices' => $this->getOuiNonChoices())));
            if (!$this->etablissement->isNegociant()) {
                $this->setWidget('raisins_mouts', new sfWidgetFormChoice(array('choices' => $this->getOuiNonChoices())));
                $this->setWidget('exclusion_drm', new sfWidgetFormChoice(array('choices' => $this->getOuiNonChoices())));
                $this->setWidget('type_dr', new sfWidgetFormChoice(array('choices' => $this->getTypeDR())));
            }
        }
//       $this->setWidget('recette_locale', new sfWidgetFormChoice(array('choices' => $this->getRecettesLocales())));
        $this->setWidget('region', new sfWidgetFormChoice(array('choices' => $this->getRegions())));


        $this->embedForm('liaisons_operateurs', new LiaisonsItemForm($this->getObject()->liaisons_operateurs));

        $this->setWidget('site_fiche', new sfWidgetFormInput());
        if ($this->etablissement->isCourtier()) {
            $this->setWidget('carte_pro', new sfWidgetFormInput());
        }
        $this->setWidget('no_accises', new sfWidgetFormInput());
        $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->widgetSchema->setLabel('nom', 'Nom du chai');
        $this->widgetSchema->setLabel('statut', 'Statut');

        if (!$this->etablissement->isCourtier()) {
            $this->widgetSchema->setLabel('cvi', 'CVI');
            $this->widgetSchema->setLabel('relance_ds', 'Relance DS');
            if (!$this->etablissement->isNegociant()) {
                $this->widgetSchema->setLabel('raisins_mouts', 'Raisins et Moûts');
                $this->widgetSchema->setLabel('exclusion_drm', 'Exclusion DRM');
                $this->widgetSchema->setLabel('type_dr', 'Type de DR');
            }
        }
//    $this->widgetSchema->setLabel('recette_locale', 'Recette Locale');
        $this->widgetSchema->setLabel('region', 'Région viticole');

        $this->widgetSchema->setLabel('site_fiche', 'Site Fiche Publique');
        if ($this->etablissement->isCourtier()) {
            $this->widgetSchema->setLabel('carte_pro', 'N° Carte professionnel');
        }
        $this->widgetSchema->setLabel('no_accises', "N° d'Accise");
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');


        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('statut', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getStatuts()))));
        if (!$this->etablissement->isCourtier()) {
            $this->setValidator('cvi', new sfValidatorString(array('required' => false)));
            $this->setValidator('relance_ds', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getOuiNonChoices()))));
            if (!$this->etablissement->isNegociant()) {
                $this->setValidator('raisins_mouts', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getOuiNonChoices()))));
                $this->setValidator('exclusion_drm', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getOuiNonChoices()))));
                $this->setValidator('type_dr', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypeDR()))));
            }
        }
//   $this->setValidator('recette_locale', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getRecettesLocales()))));
        $this->setValidator('region', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getRegions()))));

        $this->setValidator('site_fiche', new sfValidatorString(array('required' => false)));
        if ($this->etablissement->isCourtier()) {
            $this->setValidator('carte_pro', new sfValidatorString(array('required' => false)));
        }
        $this->setValidator('no_accises', new sfValidatorString(array('required' => false)));
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setNameFormat('etablissement_modification[%s]');
    }

    public function getStatuts() {
        return EtablissementClient::getStatuts();
    }

    public function getOuiNonChoices() {
        return array('oui' => 'Oui', 'non' => 'Non');
    }

    public function getRecettesLocales() {
        return EtablissementClient::getRecettesLocales();
    }

    public function getRegions() {
        return EtablissementClient::getRegions();
    }

    public function getTypeDR() {
        return EtablissementClient::getTypeDR();
    }
    
     protected function doSave($con = null) {
        if (null === $con) {
            $con = $this->getConnection();
        }
         $this->updateObject();
         
         $this->etablissement->remove('liaisons_operateurs');
         $this->etablissement->add('liaisons_operateurs');
         
        foreach ($this->getEmbeddedForms() as $key => $form) {
            
            foreach ($this->values[$key] as $liaison){
                $this->etablissement->addLiaison($liaison['type_liaison'], EtablissementClient::getInstance()->find($liaison['id_etablissement']));
            }
        }
        $this->object->getCouchdbDocument()->save();
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if ($form instanceof LiaisonsItemForm) {
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
        $etablissement = new Etablissement();
        $form_embed = new LiaisonItemForm($etablissement->liaisons_operateurs->add());
        $form = new EtablissementCollectionTemplateForm($this, 'liaisons_operateurs', $form_embed);
        return $form->getFormTemplate();
    }

    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->liaisons_operateurs->remove($key);
    }

}

?>
