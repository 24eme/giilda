<?php

class DSNegoceEtablissementPeriodesForm extends BaseForm {

    private $isTeledeclarationMode = false;

    public function configure() {
        $list = $this->getChoicePeriodes();
        $this->setWidgets(array(
            'periode' => new bsWidgetFormChoice(array('choices' => $list, 'default' => $this->default_date), array('class' => 'select2 select2SubmitOnChange form-control', 'style' => 'width: 18rem;'))
        ));
        $this->setValidators(array(
            'periode' => new sfValidatorChoice(array('required' => true, 'choices' => $list))
        ));
        $this->widgetSchema->setLabels(array(
            'periode' => "Historique"
        ));
        $this->widgetSchema->setNameFormat('%s');
    }

    public function __construct($identifiantEtablissement, $defaultDate, $isTeledeclarationMode = false) {
        $this->etablissement_id = $identifiantEtablissement;
        $this->default_date = $defaultDate;
        $this->isTeledeclarationMode = $isTeledeclarationMode;
        return parent::__construct();
    }

    private function getChoicePeriodes() {
        $periodes = DSNegoceClient::getInstance()->listPeriodesByEtablissementId($this->etablissement_id);
        return $periodes;
    }

}
