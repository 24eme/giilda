<?php

class bsWidgetFormChoice extends sfWidgetFormChoice
{
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->addOption('inline', false);
    }

    public function getRenderer()
    {
        if ($this->getOption('renderer'))
        {
          return $this->getOption('renderer');
        }

        if (!$class = $this->getOption('renderer_class'))
        {
          $type = !$this->getOption('expanded') ? '' : ($this->getOption('multiple') ? 'checkbox' : 'radio');
          $class = sprintf('bsWidgetFormSelect%s', ucfirst($type));
        }

        $options = $this->options['renderer_options'];
        $options['inline'] = $this->options['inline'];

        $options['choices'] = new sfCallable(array($this, 'getChoices'));

        $renderer = new $class($options, $this->getAttributes());

        // choices returned by the callback will already be translated (so we need to avoid double-translation)
        if ($renderer->hasOption('translate_choices')) {
            $renderer->setOption('translate_choices', false);
        }

        $renderer->setParent($this->getParent());

        return $renderer;
    }

}