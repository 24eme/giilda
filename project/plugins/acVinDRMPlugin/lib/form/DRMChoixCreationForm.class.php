<?php

class DRMChoixCreationForm extends BaseForm {

    private $periode = null;
    private $identifiant = null;

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->periode = $options['periode'];
        $this->identifiant = $options['identifiant'];
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'type_creation' => new bsWidgetFormChoice(array('expanded' => true, 'inline' => false, 'choices' => $this->getTypesCreation())),
            'file' => new sfWidgetFormInputFile()
        ));
        $this->widgetSchema->setLabels(array(
            'type_creation' => 'Type de création : ',
            'file' => "Fichier d'import de votre logiciel tiers"
        ));
        $this->setValidators(array(
            'type_creation' => new sfValidatorChoice(array('multiple' => false,  'required' => true, 'choices' => array_keys($this->getTypesCreation()))),
            'file' => new ValidatorImportCsv(array('file_path' => sfConfig::get('sf_data_dir') . '/upload'))
        ));
        $this->widgetSchema['type_creation']->setDefault(DRMClient::DRM_CREATION_VIERGE);
        $this->widgetSchema->setNameFormat('drmChoixCreation[%s]');
    }

    public function getTypesCreation() {
        $choice_type_creation = array();
        if(DRMConfiguration::getInstance()->getRepriseDonneesUrl()){
          $choice_type_creation = array_merge(array(DRMClient::DRM_CREATION_DOCUMENTS => "Création d'une drm pré-remplie"),$choice_type_creation);
        }
        return array_merge($choice_type_creation, DRMClient::$typesCreationLibelles);
    }

}
