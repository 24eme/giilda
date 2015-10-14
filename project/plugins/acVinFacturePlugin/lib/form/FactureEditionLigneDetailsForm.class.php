<?php

class FactureEditionLigneDetailsForm extends acCouchdbObjectForm {

    private $sans_categories = false;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        if (array_key_exists('sans_categories', $options)) {
            $this->sans_categories = $options['sans_categories'];
        }
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->getObject() as $detail) {
            $this->embedForm($detail->getKey(), new FactureEditionLigneDetailForm($detail));
        }

        $this->widgetSchema->setNameFormat('facture_edition_ligne_details[%s]');
    }

}
