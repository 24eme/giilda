<?php

class DRMValidationCoordonneesSocieteForm extends acCouchdbObjectForm {

    protected $coordonneesSociete = null;
    protected $drm = null;

    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->drm = $drm;
        parent::__construct($drm, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();

        $this->setWidget('siret', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('siret', 'SIRET :');

        $this->setWidget('adresse', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('adresse', 'Adresse :');

        $this->setWidget('code_postal', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('code_postal', 'Code postal :');

        $this->setWidget('commune', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('commune', 'Commune :');

        $this->setWidget('email', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('email', 'E-mail :');

        $this->setWidget('telephone', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setLabel('telephone', 'Téléphone :');

        $this->setWidget('fax', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setLabel('fax', 'Fax :');

        $this->widgetSchema->setNameFormat('drm_validation_coordonnees_societe[%s]');
    }

    private function getCoordonneesSociete() {
        if (!$this->coordonneesSociete) {
            $this->coordonneesSociete = $this->drm->getCoordonneesSociete();
        }
        return $this->coordonneesSociete;
    }

}
