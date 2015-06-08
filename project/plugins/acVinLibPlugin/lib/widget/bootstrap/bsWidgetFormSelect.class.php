<?php

class bsWidgetFormSelect extends sfWidgetFormSelect {
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->addOption('inline', false);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        if ($this->getOption('multiple'))
        {
          $attributes['multiple'] = 'multiple';

          if ('[]' != substr($name, -2))
          {
            $name .= '[]';
          }
        }

        $choices = $this->getChoices();

        $classes = isset($attributes['class']) ? $attributes['class'] : '';
        $attributes['class'] = trim('form-control ' . $classes);

        return $this->renderContentTag('select', "\n".implode("\n", $this->getOptionsForSelect($value, $choices))."\n", array_merge(array('name' => $name), $attributes));
    }
}