<?php

class AlertesStatutsModificationForm extends sfForm {

    private $alertesList = null;

    public function __construct($alertesList, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->alertesList = $alertesList;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('statut_all_alertes', new sfWidgetFormChoice(array('choices' => $this->getStatuts(), 'expanded' => false)));
        $this->setWidget('commentaire_all_alertes', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        foreach ($this->alertesList as $a) {

            $alerte_id = null;
            if ($a instanceof Elastica_Result) {
                $alerte = $a->getData()['doc'];
                $alerte_id = $alerte['_id'];
            } else {
                $alerte_id = $a->id;
            }

            $this->setWidget($alerte_id, new sfWidgetFormInputCheckbox());
            $this->setValidator($alerte_id, new sfValidatorChoice(array('required' => false, 'choices' => array('0' => 0, '1' => 1))));
        }

        $this->setValidator('commentaire_all_alertes', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setLabel('commentaire_all_alertes', 'Commentaire :');

        $this->widgetSchema->setLabel('statut_all_alertes', 'Choisir un statut pour toutes les alertes : ');
        $this->setValidator('statut_all_alertes', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));
    }

    private function getStatuts() {
        return AlerteClient::getStatutsOperateursWithLibelles();
    }

}
