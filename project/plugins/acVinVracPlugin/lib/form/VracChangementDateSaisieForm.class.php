<?php

class VracChangementDateSaisieForm extends VracForm {

    protected $isTeledeclarationMode;

    public function __construct(Vrac $vrac, $isTeledeclarationMode = false, $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $isTeledeclarationMode;
        parent::__construct($vrac, $options, $CSRFSecret);
    }


    public function configure()
    {
        $this->setWidget('date_saisie', new bsWidgetFormInputDate());
        $dateRegexpOptions = array('required' => true,
        		'pattern' => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/",
        		'min_length' => 10,
        		'max_length' => 10);
        $dateRegexpErrors = array('required' => 'Cette obligatoire',
        		'invalid' => 'Date invalide (le format doit être jj/mm/aaaa)',
        		'min_length' => 'Date invalide (le format doit être jj/mm/aaaa)',
        		'max_length' => 'Date invalide (le format doit être jj/mm/aaaa)');

        $this->setValidator('date_saisie', new sfValidatorDate(array('date_output' => 'c', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)));

        $this->widgetSchema->setNameFormat('vrac[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if($this->getValidator('date_saisie') instanceof sfValidatorDate) {
            $this->setDefault('date_saisie',
                DateTime::createFromFormat('c', $this->getObject()->valide->date_saisie)->format('d/m/Y')
            );
        }
    }

    public function doUpdateObject($values)
    {
        parent::doUpdateObject($values);
        $this->getObject()->valide->date_saisie = $values['date_saisie'];
    }

}
