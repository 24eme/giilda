<?php

class bsWidgetFormInputDate extends bsWidgetFormInput
{
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $input = parent::render($name, $value, $attributes, $errors);
        $icon = $this->renderContentTag('span', $this->renderTag('span', array('class' => 'glyphicon glyphicon-calendar')), array('class' => 'input-group-addon'));

        return $this->renderContentTag('div', $input.$icon, array('class' => 'input-group date'));
    }
}