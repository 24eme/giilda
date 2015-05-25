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
        $this->setValidator('cvi', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('cvi', 'CVI :');

        $this->setWidget('adresse', new sfWidgetFormInput());
        $this->setValidator('adresse', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('adresse', 'Adresse :');

        $this->setWidget('code_postal', new sfWidgetFormInput());
        $this->setValidator('code_postal', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('code_postal', 'Code postal :');

        $this->setWidget('commune', new sfWidgetFormInput());
        $this->setValidator('commune', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('commune', 'Commune :');

        $this->setWidget('no_accises', new sfWidgetFormInput());
        $this->setValidator('no_accises', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setLabel('no_accises', 'Accise :');

        $this->widgetSchema->setNameFormat('drm_validation_coordonnees_etablissement[%s]');
    }

    private function getCoordonneesEtablissement() {
        if (!$this->coordonneesEtablissement) {
            $this->coordonneesEtablissement = $this->drm->getDeclarant();
        }
        return $this->coordonneesEtablissement;
    }
    
     public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $this->getCoordonneesEtablissement();
        $this->setDefault('cvi', $this->coordonneesEtablissement->cvi);
        $this->setDefault('adresse', $this->coordonneesEtablissement->adresse);
        $this->setDefault('code_postal', $this->coordonneesEtablissement->code_postal);
        $this->setDefault('commune', $this->coordonneesEtablissement->commune);
        $this->setDefault('accise', $this->coordonneesEtablissement->no_accises);
    }
    
      public function getDiff() {  
        $diff = array();
        $this->getCoordonneesEtablissement();
        foreach ($this->getValues() as $key => $new_value) {
            if(!preg_match('/^_revision$/', $key)){
                if($this->coordonneesEtablissement->$key != $new_value){
                    $diff[$key] = $new_value;
                    }
            }
        }
        return $diff;
    }

}
