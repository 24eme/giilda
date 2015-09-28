<?php

class bsWidgetFormSelect extends sfWidgetFormSelect {
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->addOption('inline', true);
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

        if(!isset($attributes['class'])) {
            $attributes['class'] = 'form-control';            
        }

        return $this->renderContentTag('select', "\n".implode("\n", $this->getOptionsForSelect($value, $choices))."\n", array_merge(array('name' => $name), $attributes));
    }
}