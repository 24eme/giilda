<?php

class FacturePaiementForm extends acCouchdbObjectForm {

    public function configure()
    {
        $this->setWidget('montant_paiement', new sfWidgetFormInputFloat());
        $this->setValidator('montant_paiement', new sfValidatorNumber(array('required' => true)));

        $this->setWidget('date_paiement', new sfWidgetFormInput(array(), array()));
        $this->setValidator('date_paiement', new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)));

        $this->setWidget('reglement_paiement', new sfWidgetFormInput());
        $this->setValidator('reglement_paiement', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setNameFormat('facture_paiement[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if($this->getObject()->date_paiement) {
            $date = new DateTime($this->getObject()->date_paiement);
            $this->setDefault('date_paiement', $date->format('d/m/Y'));
        }

        $this->setDefault('montant_paiement', $this->getObject()->total_ttc);
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->updateMontantPaiement();
    }

}
