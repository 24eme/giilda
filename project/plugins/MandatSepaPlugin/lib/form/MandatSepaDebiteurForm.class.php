<?php
class MandatSepaDebiteurForm extends acCouchdbObjectForm {

    public function configure() {

        $this->setWidget('nom', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('nom', 'Nom associé au compte bancaire :');
        $this->setValidator('nom', new sfValidatorString(array('required' => true)));

        $this->setWidget('adresse', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('adresse', 'Adresse :');
        $this->setValidator('adresse', new sfValidatorString(array('required' => true)));

        $this->setWidget('commune', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('commune', 'Commune :');
        $this->setValidator('commune', new sfValidatorString(array('required' => true)));

        $this->setWidget('code_postal', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('code_postal', 'Code Postal :');
        $this->setValidator('code_postal', new sfValidatorString(array('required' => true)));

        $this->setWidget('iban', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('iban', 'IBAN :');
        $this->setValidator('iban', new ValidatorIban(array('required' => true)));

        $this->setWidget('bic', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('bic', 'BIC :');
        $this->setValidator('bic', new sfValidatorRegex(array('pattern' => '/^[a-z]{6}[2-9a-z][0-9a-np-z]([a-z0-9]{3}|x{3})?$/i', 'required' => true), array('invalid' => 'Numéro BIC invalide')));

        $this->widgetSchema->setNameFormat('mandat_sepa_debiteur[%s]');
    }
}
