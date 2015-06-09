<?php

class bsWidgetFormSelectRadio extends sfWidgetFormSelectRadio {

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
            'name'  => substr($name, 0, -2),
            'type'  => 'radio',
            'value' => self::escapeOnce($key),
            'id'    => $id = $this->generateId($name, self::escapeOnce($key)),
            );

            if (strval($key) == strval($value === false ? 0 : $value))
            {
            $baseAttributes['checked'] = 'checked';
            }

            //$inputs[$id] = $this->renderContentTag('label', $this->renderTag('input', array_merge($baseAttributes, $attributes)) . '&nbsp;' . self::escapeOnce($option), array('for' => $id, 'class' => 'radio-inline'));
            $inputs[$id] = $this->renderContentTag('label', $this->renderTag('input', array_merge($baseAttributes, $attributes)) . '&nbsp;' . $option, array('for' => $id, 'class' => 'radio-inline'));
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
                $rows[] = $this->renderContentTag('div', $input, array('class' => 'radio'));
            }
        }

        return !$rows ? '' : implode($this->getOption('separator'), $rows);
    }
}