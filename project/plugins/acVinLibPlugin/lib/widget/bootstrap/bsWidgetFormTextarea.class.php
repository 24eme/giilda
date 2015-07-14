<?php
class bsWidgetFormTextarea extends sfWidgetFormTextarea
{
	
	public function render($name, $value = null, $attributes = array(), $errors = array())
	{

		if(!isset($attributes['class'])) {
			$attributes['class'] = 'form-control';
		}
		return $this->renderContentTag('textarea', self::escapeOnce($value), array_merge(array('name' => $name), $attributes));
	}
	
}