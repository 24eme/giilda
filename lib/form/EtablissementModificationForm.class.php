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

    public function __construct(Etablissement $etablissement, $options = array(), $CSRFSecret = null) {
        $this->etablissement = $etablissement;
        parent::__construct($etablissement, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('nom', new sfWidgetFormInput());
        $this->setWidget('statut', new sfWidgetFormChoice(array('choices' => $this->getStatuts())));

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

        $this->setWidget('type_liaison', new sfWidgetFormChoice(array('choices' => $this->getTypesLiaisons())));
        foreach ($this->getObject()->liaisons_operateurs as $key => $liaison_etablissement) {
            $this->setWidget('liaisons_operateurs[' . $key . ']', new WidgetEtablissement(array('interpro_id' => $this->getObject()->interpro), array('class' => 'autocomplete')));
        }

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
        $this->widgetSchema->setLabel('type_liaison', 'Type de liaison (externe)');

        foreach ($this->getObject()->liaisons_operateurs as $key => $liaison_etablissement) {
            $this->widgetSchema->setLabel('liaisons_operateurs[' . $key . ']', 'Établissement');
        }
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
        $this->setValidator('type_liaison', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypesLiaisons()))));

        foreach ($this->getObject()->liaisons_operateurs as $key => $liaison_etablissement) {
            $this->setValidator('liaisons_operateurs[' . $key . ']', new ValidatorEtablissement(array('required' => true)));
        }
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

    public function getTypesLiaisons() {
        return EtablissementClient::getTypesLiaisons();
    }

}

?>
