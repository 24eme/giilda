<?php

class WidgetCompte extends bsWidgetFormInput
{
    protected $identifiant = null;

    public function __construct($options = array(), $attributes = array())
    {
        parent::__construct($options, $attributes);

        $this->setAttribute('data-ajax', $this->getUrlAutocomplete());
    }

    protected function configure($options = array(), $attributes = array())
    {
      parent::configure($options, $attributes);

      $this->addRequiredOption('interpro_id', null);
      if(!count($attributes))
        $this->setAttribute('class', 'autocomplete');
    }

    public function setOption($name, $value) {
        parent::setOption($name, $value);
        return $this;
    }

    public function getUrlAutocomplete() {
        $interpro_id = $this->getOption('interpro_id');
        return sfContext::getInstance()->getRouting()->generate('soc_etb_com_autocomplete_actif', array('interpro_id' => $interpro_id));
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
  {
      $this->identifiant = $value;

      if($this->identifiant) {
          $compte = CompteClient::getInstance()->find($this->identifiant);

          if(!$compte) {
              $value = null;
          } else {
              $value = $compte->_id.",".$compte->nom;
          }
      }

      return parent::render($name, $value, $attributes, $errors);
  }

}
