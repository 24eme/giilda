<?php

class FacturePaiementEmbedForm extends acCouchdbObjectForm {

    public function configure()
    {
        $this->setWidget('montant', new bsWidgetFormInputFloat());
        $this->setValidator('montant', new sfValidatorNumber(array('required' => false)));

        $this->setWidget('date', new sfWidgetFormInput(array(), array()));
        $this->setValidator('date', new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)));

        $this->setWidget('type_reglement', new sfWidgetFormChoice(array('choices' => $this->getTypesPaiements())));
        $this->setValidator('type_reglement', new sfValidatorChoice(array('choices' => array_keys($this->getTypesPaiements()), 'required' => false)));

        $this->setWidget('commentaire', new sfWidgetFormTextarea());
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setNameFormat('facture_paiement[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if($this->getObject()->date) {
            $date = new DateTime($this->getObject()->date);
            $this->setDefault('date', $date->format('d/m/Y'));
        }else{
            $this->setDefault('date', date('d/m/Y'));
        }
        if(!$this->getObject()->montant) {
            $this->setDefault('montant', $this->getObject()->getDocument()->total_ttc - $this->getObject()->getDocument()->getMontantPaiement());
        }
    }


    protected function getTypesPaiements(){
      return array_merge(array("" => ""),FactureClient::$types_paiements);
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

}
