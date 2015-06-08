<?php

class bsWidgetFormInput extends sfWidgetFormInput
{
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $classes = isset($attributes['class']) ? $attributes['class'] : '';
        $attributes['class'] = trim('form-control ' . $classes);
        
        return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
    }
}