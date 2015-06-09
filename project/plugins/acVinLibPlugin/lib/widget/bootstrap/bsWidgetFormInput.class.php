<?php

class bsWidgetFormInput extends sfWidgetFormInput
{
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        if(!isset($attributes['class'])) {
            $attributes['class'] = 'form-control';            
        }
        
        return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
    }
}