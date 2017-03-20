<?php

class SV12EtablissementChoiceForm extends EtablissementChoiceForm {

    protected $autofocus = false;

    public function __construct($interpro_id, $defaults = array(), $options = array(), $CSRFSecret = null) {
        if(isset($options['autofocus']) && $options['autofocus'] && $options['autofocus'] == 'autofocus'){
            $this->autofocus = true;
        }
        parent::__construct($interpro_id, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->configureFamilles(array(EtablissementFamilles::FAMILLE_NEGOCIANT));
        if ($this->autofocus) {
            $this->configureAutfocus();
        }
    }

}
