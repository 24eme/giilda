<?php

class DRMDetailCreationVracItemForm extends acCouchdbObjectForm {

    protected $isTeledeclarationMode = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $options['isTeledeclarationMode'];
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->setWidget('identifiant', new bsWidgetFormInput());
        $this->setWidget('acheteur', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-declaration', 'familles' => EtablissementFamilles::FAMILLE_NEGOCIANT)));
        $this->setWidget('prixhl', new bsWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('volume', new bsWidgetFormInputFloat(array(), array('autocomplete' => 'off')));

        $this->setValidator('identifiant', new sfValidatorNumber(array('required' => true), array('required' => "identifiant contrat obligatoire")));
        $this->setValidator('prixhl', new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));
        $this->setValidator('volume', new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));

        $post_validator_class = $this->getPostValidatorClass();
        $this->validatorSchema->setPostValidator(new $post_validator_class());
        $this->widgetSchema->setNameFormat(sprintf("%s[%%s]", $this->getFormName()));
//        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
//        if(!$this->getObject()->date_enlevement) $this->setDefault('date_enlevement', $this->getObject()->getDocument()->getDate());
//
//        $date = new DateTime($this->getDefault('date_enlevement'));
//        $this->setDefault('date_enlevement', $date->format('d/m/Y'));
    }

    public function getAcheteurChoices(){

    }


    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $date = new DateTime($this->getObject()->getDocument()->getDate());
        $this->getObject()->date_enlevement = $date->format('Y-m-d');
    }

    public function getProduitDetail() {

        return $this->getObject()->getProduitDetail();
    }

    public function getFormName() {

        return "drm_detail_creationvrac_item";
    }

    public function getPostValidatorClass() {

        return 'DRMDetailVracItemValidator';
    }

}
