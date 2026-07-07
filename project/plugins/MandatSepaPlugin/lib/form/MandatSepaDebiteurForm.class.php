<?php
class MandatSepaDebiteurForm extends acCouchdbObjectForm {

    protected $adminMode;

    public function __construct(acCouchdbJson $object, $adminMode = false, $options = array(), $CSRFSecret = null) {
        $this->adminMode = $adminMode;
        parent::__construct($object, $options, $CSRFSecret);
    }

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

        $this->setWidget('banque_nom', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('banque_nom', 'Nom de la banque :');
        $this->setValidator('banque_nom', new sfValidatorString(array('required' => true)));

        $this->setWidget('banque_commune', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('banque_commune', 'Commune de la banque :');
        $this->setValidator('banque_commune', new sfValidatorString(array('required' => false)));

        $this->setWidget('iban', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('iban', 'IBAN :');
        $this->setValidator('iban', new ValidatorIban(array('required' => true)));

        $this->setWidget('bic', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('bic', 'BIC :');
        $this->setValidator('bic', new sfValidatorRegex(array('pattern' => '/^[a-z]{6}[2-9a-z][0-9a-np-z]([a-z0-9]{3}|x{3})?$/i', 'required' => true), array('invalid' => 'Numéro BIC invalide')));

        if ($this->adminMode) {
            $this->setWidget('is_signe', new bsWidgetFormInputCheckbox());
            $this->widgetSchema->setLabel('is_signe', 'Mandat signé :');
            $this->setValidator('is_signe', new ValidatorBoolean(array('required' => false)));

            $this->setWidget('is_actif', new bsWidgetFormInputCheckbox());
            $this->widgetSchema->setLabel('is_actif', 'Prélèvement actif :');
            $this->setValidator('is_actif', new ValidatorBoolean(array('required' => false)));
        }

        $this->widgetSchema->setNameFormat('mandat_sepa_debiteur[%s]');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (isset($values['is_signe']) && $values['is_signe']) {
        	$this->getObject()->getDocument()->set('is_signe', 1);
        } else {
            $this->getObject()->getDocument()->set('is_signe', 0);
        }
        if (isset($values['is_actif']) && $values['is_actif']) {
        	$this->getObject()->getDocument()->set('is_actif', 1);
        } else {
            $this->getObject()->getDocument()->set('is_actif', 0);
        }
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $defaults = $this->getDefaults();
        if ($this->getObject()->getDocument()->is_signe) {
            $defaults['is_signe'] = 1;
        }
        if ($this->getObject()->getDocument()->is_actif) {
            $defaults['is_actif'] = 1;
        }
        $this->setDefaults($defaults);
    }
}
