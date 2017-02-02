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
        if(DRMConfiguration::getInstance()->isDRMNegoce()){
          $familles = array_merge($familles,array(EtablissementFamilles::FAMILLE_NEGOCIANT,EtablissementFamilles::FAMILLE_COOPERATIVE));
        }
        $this->configureFamilles($familles);
        if ($this->autofocus) {
            $this->configureAutfocus();
        }
    }

}
