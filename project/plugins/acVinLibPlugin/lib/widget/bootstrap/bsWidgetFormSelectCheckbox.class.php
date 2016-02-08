<?php

class bsWidgetFormSelectCheckbox extends sfWidgetFormSelectCheckbox {

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->addOption('inline', true);
    }

    protected function formatChoices($name, $value, $choices, $attributes)
    {
        $inputs = array();
        foreach ($choices as $key => $option)
        {
          $baseAttributes = array(
            'name'  => $name,
            'type'  => 'checkbox',
            'value' => self::escapeOnce($key),
            'id'    => $id = $this->generateId($name, self::escapeOnce($key)),
          );

          if ((is_array($value) && in_array(strval($key), $value)) || (is_string($value) && strval($key) == strval($value)))
          {
            $baseAttributes['checked'] = 'checked';
          }

          $inputs[$id] = $this->renderContentTag('label', $this->renderTag('input', array_merge($baseAttributes, $attributes)). '' . self::escapeOnce($option), array('for' => $id, 'class' => 'checkbox-inline'));
        }

        return call_user_func($this->getOption('formatter'), $this, $inputs);
    }

    public function formatter($widget, $inputs)
    {
        $rows = array();
        foreach ($inputs as $input)
        {
            if($this->getOption('inline')) {
                $rows[] = $input;
            } else {
                $rows[] = $this->renderContentTag('div', $input, array('class' => 'checkbox'));
            }
        }

        return !$rows ? '' : implode($this->getOption('separator'), $rows);
    }
}