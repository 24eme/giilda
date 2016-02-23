<?php
class VracForm extends acCouchdbObjectForm 
{
	public function unsetFields(array $fields = array())
	{
		foreach ($this as $name => $field) {
			if (in_array($name, $fields)) {
				unset($this[$name]);
			}
		}
	}
}