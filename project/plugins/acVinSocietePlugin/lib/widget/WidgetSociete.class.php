<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class WidgetSociete
 * @author mathurin
 */
class WidgetSociete extends sfWidgetFormChoice
{
    protected $identifiant = null;

    public function __construct($options = array(), $attributes = array())
    {
        parent::__construct($options, $attributes);

        $this->setAttribute('data-ajax', $this->getUrlAutocomplete());
        $this->setOption('choices', $this->getChoices());
    }

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->setOption('choices', array());
        $this->addOption('type_societe', array());
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
        return sfContext::getInstance()->getRouting()->generate('societe_autocomplete_all', array('interpro_id' => $interpro_id));
    }

    public function getChoices() {
        if(!$this->identifiant) {
            return array();
        }
        $societes = SocieteAllView::getInstance()->findBySociete($this->identifiant);
        if (!$societes) {
            return array();
        }
        
        $choices = array();
        foreach($societes as $key => $societe) {
            $choices[$societe->id] = SocieteAllView::getInstance()->makeLibelle($societe->key);
        }

        return $choices;
   }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $this->identifiant = $value;

        return parent::render($name, $value, $attributes, $errors);
    }
    
}
