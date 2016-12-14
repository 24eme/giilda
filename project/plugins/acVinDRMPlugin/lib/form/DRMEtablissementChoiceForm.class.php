<?php

class DRMEtablissementChoiceForm extends EtablissementChoiceForm {

    protected $autofocus = false;

    public function __construct($interpro_id, $defaults = array(), $options = array(), $CSRFSecret = null) {
        if(isset($options['autofocus']) && $options['autofocus'] && $options['autofocus'] == 'autofocus'){
            $this->autofocus = true;
        }
        parent::__construct($interpro_id, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $familles = array(EtablissementFamilles::FAMILLE_PRODUCTEUR);
        if(sfConfig::get('app_drmnegoce')){
          $familles = array_merge($familles,array(EtablissementFamilles::FAMILLE_NEGOCIANT));
        }
        $this->configureFamilles($familles);
        if ($this->autofocus) {
            $this->configureAutfocus();
        }
    }

}
