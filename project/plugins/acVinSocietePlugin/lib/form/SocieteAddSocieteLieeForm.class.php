<?php

class SocieteAddSocieteLieeForm extends acCouchdbForm
{
    public function __construct(Societe $societe, $options = array(), $CSRFSecret = null) {
        parent::__construct($societe, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();

        $this->setWidget('societe-liee', new WidgetSociete(['interpro_id' => $this->getDocument()->interpro]));
        $this->widgetSchema->setLabel('societe-liee', 'Société liée *');
        $this->setValidator('societe-liee', new ValidatorSociete(['required' => true]));
        $this->widgetSchema->setNameFormat('societe_societeliee[%s]');
    }

    public function save() {
        $values = $this->getValues();
        $this->getDocument()->getOrAdd('societes_liees')->add(null, $values['societe-liee']);
        $societeReverse = SocieteClient::getInstance()->find($values['societe-liee']);
        $societeReverse->getOrAdd('societes_liees')->add(null, $this->getDocument()->_id);

        $this->getDocument()->save();
        $societeReverse->save();
    }
}
