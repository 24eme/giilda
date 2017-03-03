<?php
class RegexpStatFilter extends StatFilter
{
	protected $regexp;
	
	public function __construct($regexp)
	{
		$this->regexp = $regexp;
	}
	
	public function match($value)
	{
		return preg_match($this->regexp, $value);
	}
}