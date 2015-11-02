<?php

class bsWidgetFormInputFloat extends sfWidgetFormInputText
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addOption('decimal', 2);
    $this->addOption('decimal_auto', 2);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfApplicationConfiguration::getActive()->loadHelpers('Float');
    
    $int = $value;
    $float = null;
    if(count(explode(".", $value)) >= 2) {
      list($int, $float) = explode(".", $value);
    }
    $format = ($value !== null && trim($value) !== "" && strlen($float) <= $this->getOption('decimal_auto'));

    if($format) {
      $value = sprintFloat($value, "%01.0".$this->getOption('decimal_auto')."f")  ;
    }

    $attributes['autocomplete'] = 'off';
    $attributes['data-decimal'] = $this->getOption('decimal');
    $attributes['data-decimal-auto'] = $this->getOption('decimal_auto');
    
    if(!isset($attributes['class'])) {
      $attributes['class'] = 'form-control text-right input-float';            
    }

    return parent::render($name, $value, $attributes, $errors);
  }
}