<?php

class bsWidgetFormInputInteger extends sfWidgetFormInputText
{
  protected function configure($options = array(), $attributes = array())
  {
      parent::configure($options, $attributes);
      if(!$this->getAttribute('class')) {
          $this->setAttribute('class', 'form-control text-right input-integer');
      }
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
      $attributes['autocomplete'] = 'off';
      
      return parent::render($name, $value, $attributes, $errors);
  }
}