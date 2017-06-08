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
        $this->setValidator('cvi', new sfValidatorString(array('required' => true), array('required' => 'Le cvi est obligatoire.')));
        $this->widgetSchema->setLabel('cvi', 'CVI :');

        $this->setWidget('adresse', new sfWidgetFormInput());
        $this->setValidator('adresse', new sfValidatorString(array('required' => true), array('required' => 'L\'adresse est obligatoire.')));
        $this->widgetSchema->setLabel('adresse', 'Adresse :');

        $this->setWidget('code_postal', new sfWidgetFormInput());
        $this->setValidator('code_postal', new sfValidatorString(array('required' => true), array('required' => 'Le code postal est obligatoire.')));
        $this->widgetSchema->setLabel('code_postal', 'Code postal :');

        $this->setWidget('commune', new sfWidgetFormInput());
        $this->setValidator('commune', new sfValidatorString(array('required' => true), array('required' => 'Le code postal est obligatoire.')));
        $this->widgetSchema->setLabel('commune', 'Commune :');

        $this->setWidget('no_accises', new sfWidgetFormInput());
        $this->setValidator('no_accises', new sfValidatorString(array('required' => true), array('required' => 'Le numéro d\'accise est obligatoire.')));
        $this->widgetSchema->setLabel('no_accises', 'Accises :');

        if ($this->drm->declarant->exist('adresse_compta')) {
            $this->setWidget('adresse_compta', new sfWidgetFormInput());
            $this->setValidator('adresse_compta', new sfValidatorString(array('required' => false)));
            $this->widgetSchema->setLabel('adresse_compta', 'Lieu de la comptabilité matière :');
        }
        if ($this->drm->declarant->exist('caution')) {
            $this->setWidget('caution', new sfWidgetFormChoice(array('expanded' => true, 'multiple' => false, 'choices' => $this->getCautionTypes())));
            $this->setValidator('caution', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCautionTypes())), array('required' => "Aucune caution n'a été choisie")));
            $this->widgetSchema->setLabel('caution', 'Type caution :');
        }
        if ($this->drm->declarant->exist('raison_sociale_cautionneur')) {
            $this->setWidget('raison_sociale_cautionneur', new sfWidgetFormInput());
            $this->setValidator('raison_sociale_cautionneur', new sfValidatorString(array('required' => false)));
            $this->widgetSchema->setLabel('raison_sociale_cautionneur', 'Raison sociale cautionneur :');
        }


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
        $this->setDefault('no_accises', $this->coordonneesEtablissement->no_accises);

        if ($this->drm->declarant->exist('adresse_compta')) {
            $this->setDefault('adresse_compta', $this->coordonneesEtablissement->adresse_compta);
        }

        if($this->coordonneesEtablissement->caution) {
            $this->setDefault('caution', $this->coordonneesEtablissement->caution);
        }

        if ($this->drm->declarant->exist('raison_sociale_cautionneur')) {
            $this->setDefault('raison_sociale_cautionneur', $this->coordonneesEtablissement->raison_sociale_cautionneur);
        }
    }

    public function getDiff() {
        $diff = array();
        $this->getCoordonneesEtablissement();
        foreach ($this->getValues() as $key => $new_value) {
            if (!preg_match('/^_revision$/', $key)) {
                if ($this->coordonneesEtablissement->$key != $new_value) {
                    $diff[$key] = $new_value;
                }
            }
        }
        return $diff;
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->drm->declarant->cvi = $values['cvi'];
        $this->drm->declarant->adresse = $values['adresse'];
        $this->drm->declarant->code_postal = $values['code_postal'];
        $this->drm->declarant->commune = $values['commune'];
        $this->drm->declarant->no_accises = $values['no_accises'];
        if ($this->drm->declarant->exist('adresse_compta')) {
            $this->drm->declarant->adresse_compta = $values['adresse_compta'];
        }
        if ($this->drm->declarant->exist('caution')) {
            $this->drm->declarant->caution = $values['caution'];
        }

        if ($this->drm->declarant->exist('raison_sociale_cautionneur')) {
            $this->drm->declarant->raison_sociale_cautionneur = $values['raison_sociale_cautionneur'];
        }

        if ($this->drm->declarant->caution != EtablissementClient::CAUTION_CAUTION) {
            $this->drm->declarant->raison_sociale_cautionneur = null;
        }
    }
    
    private function getCautionTypes() {
        $cautionsType = array();
        foreach (EtablissementClient::$caution_libelles as $key => $value) {
          if($key == EtablissementClient::CAUTION_DISPENSE){
            $cautionsType[0] = $value;
          }else{
            $cautionsType[1] = $value;
          }
        }
        return $cautionsType;
    }

}
