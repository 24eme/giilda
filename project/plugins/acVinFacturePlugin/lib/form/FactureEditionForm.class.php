<?php

class FactureEditionForm extends acCouchdbObjectForm {

    private $sans_categories = false;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        if (array_key_exists('sans_categories', $options)) {
            $this->sans_categories = $options['sans_categories'];
        }
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        var_dump(count($this->getObject()->lignes));
        if (!$this->sans_categories || count($this->getObject()->lignes) < 1) {
            $this->getObject()->lignes->add("nouvelle");
        }
        $this->embedForm('lignes', new FactureEditionLignesForm($this->getObject()->lignes, array('sans_categories' => $this->sans_categories)));

        $this->widgetSchema->setNameFormat('facture_edition[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);

        if ($this->getObject()->lignes->exist("nouvelle")) {
            $newLine = $this->getObject()->lignes->get("nouvelle")->toArray(true, false);
            $this->getObject()->lignes->remove("nouvelle");
            $this->getObject()->lignes->add(uniqid(), $newLine);
        }

        $this->getObject()->lignes->cleanLignes();
        $this->getObject()->updateTotaux();
    }

}
