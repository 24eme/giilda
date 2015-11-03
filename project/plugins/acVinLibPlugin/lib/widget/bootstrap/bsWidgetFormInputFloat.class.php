<?php

class bsWidgetFormInputFloat extends sfWidgetFormInputText
{
  protected function configure($options = array(), $attributes = array())
  {
      parent::configure($options, $attributes);
      $this->addOption('default_decimal_format', FloatHelper::getInstance()->getDefaultDecimalFormat());
      $this->addOption('max_decimal_authorized', FloatHelper::getInstance()->getMaxDecimalAuthorized());
      if(!$this->getAttribute('class')) {
          $this->setAttribute('class', 'form-control text-right input-float');
      }
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
      $defaultDecimalFormat = $this->getOption('default_decimal_format', FloatHelper::getInstance()->getDefaultDecimalFormat());
      $maxDecimalAuhtorized = $this->getOption('max_decimal_authorized', FloatHelper::getInstance()->getMaxDecimalAuthorized());
      
      $value = FloatHelper::getInstance()->format($value, $defaultDecimalFormat, $maxDecimalAuhtorized);
      
      $attributes['autocomplete'] = 'off';
      $attributes['data-decimal-auto'] = $defaultDecimalFormat;
      $attributes['data-decimal'] = $maxDecimalAuhtorized;
      
      return parent::render($name, $value, $attributes, $errors);
  }
}