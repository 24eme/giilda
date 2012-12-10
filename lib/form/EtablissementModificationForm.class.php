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

    public function __construct(Etablissement $etablissement, $options = array(), $CSRFSecret = null) {
        parent::__construct($etablissement, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('nom', new sfWidgetFormInput());
        $this->setWidget('statut', new sfWidgetFormChoice(array('choices' => $this->getStatuts())));
        $this->setWidget('cvi', new sfWidgetFormInput());
        $this->setWidget('raisins_mouts', new sfWidgetFormChoice(array('choices' => $this->getOuiNonChoices())));
        $this->setWidget('exclusion_drm', new sfWidgetFormChoice(array('choices' => $this->getOuiNonChoices())));
        $this->setWidget('relance_ds', new sfWidgetFormChoice(array('choices' => $this->getOuiNonChoices())));
 //       $this->setWidget('recette_locale', new sfWidgetFormChoice(array('choices' => $this->getRecettesLocales())));
        $this->setWidget('region', new sfWidgetFormChoice(array('choices' => $this->getRegions())));
        $this->setWidget('type_dr', new sfWidgetFormChoice(array('choices' => $this->getTypeDR())));

        $this->setWidget('type_liaison', new sfWidgetFormChoice(array('choices' => $this->getTypesLiaisons())));
        foreach ($this->getObject()->liaisons_operateurs as $key => $liaison_societe) {
            $this->setWidget('liaisons_operateurs[' . $key . ']', new WidgetSociete(array('interpro_id' => $this->getObject()->interpro), array('class' => 'autocomplete')));
        }

        $this->setWidget('site_fiche', new sfWidgetFormInput());
        $this->setWidget('carte_pro', new sfWidgetFormInput());
        $this->setWidget('no_accises', new sfWidgetFormInput());
        $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->widgetSchema->setLabel('nom', 'Nom du chai');
        $this->widgetSchema->setLabel('statut', 'Statut');
        $this->widgetSchema->setLabel('cvi', 'CVI');
        $this->widgetSchema->setLabel('raisins_mouts', 'Raisins et Moûts');
        $this->widgetSchema->setLabel('exclusion_drm', 'Exclusion DRM');
        $this->widgetSchema->setLabel('relance_ds', 'Relance DS');
    //    $this->widgetSchema->setLabel('recette_locale', 'Recette Locale');
        $this->widgetSchema->setLabel('region', 'Région viticole');
        $this->widgetSchema->setLabel('type_dr', 'Type de DR');
        $this->widgetSchema->setLabel('type_liaison', 'Type de liaison (externe)');

        foreach ($this->getObject()->liaisons_operateurs as $key => $liaison_societe) {
            $this->widgetSchema->setLabel('liaisons_operateurs[' . $key . ']', 'Société');
        }
        $this->widgetSchema->setLabel('site_fiche', 'Site Fiche Publique');
        $this->widgetSchema->setLabel('carte_pro', 'N° Carte professionnel');
        $this->widgetSchema->setLabel('no_accises', "N° d'Accise");
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');


        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('statut', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getStatuts()))));
        $this->setValidator('cvi', new sfValidatorString(array('required' => false)));
        $this->setValidator('raisins_mouts', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getOuiNonChoices()))));
        $this->setValidator('exclusion_drm', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getOuiNonChoices()))));
        $this->setValidator('relance_ds', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getOuiNonChoices()))));
     //   $this->setValidator('recette_locale', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getRecettesLocales()))));
        $this->setValidator('region', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getRegions()))));
        $this->setValidator('type_dr', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypeDR()))));
        $this->setValidator('type_liaison', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypesLiaisons()))));

        foreach ($this->getObject()->liaisons_operateurs as $key => $liaison_societe) {
            $this->setValidator('liaisons_operateurs[' . $key . ']', new ValidatorSociete(array('required' => true)));
        }
        $this->setValidator('site_fiche', new sfValidatorString(array('required' => false)));
        $this->setValidator('carte_pro', new sfValidatorString(array('required' => false)));
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

    public function getTypesLiaisons() {
        return EtablissementClient::getTypesLiaisons();
    }


}

?>
