<?php

class DRMValidationCoordonneesEtablissementForm extends acCouchdbObjectForm {

    protected $coordonneesEtablissement = null;
    protected $drm = null;

    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->drm = $drm;
        parent::__construct($drm, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();

        $this->setWidget('cvi', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('cvi', 'CVI :');

        $this->setWidget('adresse', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('adresse', 'Adresse :');

        $this->setWidget('code_postal', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('code_postal', 'Code postal :');

        $this->setWidget('commune', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('commune', 'Commune :');

        $this->setWidget('accise', new sfWidgetFormInput());
        $this->setValidator('', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('accise', 'Accise :');

        $this->widgetSchema->setNameFormat('drm_validation_coordonnees_etablissement[%s]');
    }

    private function getCoordonneesEtablissement() {
        if (!$this->coordonneesEtablissement) {
            $this->coordonneesEtablissement = $this->drm->getDeclarant();
        }
        return $this->coordonneesEtablissement;
    }

}
