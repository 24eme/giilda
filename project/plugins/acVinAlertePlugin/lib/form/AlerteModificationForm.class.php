<?php

class AlerteModificationForm extends acCouchdbObjectForm {


    public function __construct(Alerte $alerte, $options = array(), $CSRFSecret = null) {

        parent::__construct($alerte, $options, $CSRFSecret);
        $this->defaults['statut'] = $alerte->getStatut()->statut;
        $this->defaults['commentaire'] = $alerte->getStatut()->commentaire;
    }

    public function configure() {
        parent::configure();
        $this->setWidget('statut', new sfWidgetFormChoice(array('choices' => $this->getStatutsAlerte(), 'expanded' => false)));
        $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->widgetSchema->setLabel('statut','Statut');
        $this->widgetSchema->setLabel('commentaire', 'Commentaire :');

        $this->setValidator('statut', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getStatutsAlerte()))));
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setNameFormat('alerte[%s]');
    }

    public function getStatutsAlerte() {
        return AlerteClient::getStatutsOperateursWithLibelles();
    }

    public function doUpdate() {
        $this->getObject()->updateStatut($this->values['statut'],  $this->values['commentaire'], AlerteDateClient::getInstance()->getDate());
        $this->getObject()->save();
    }

}
