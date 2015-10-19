<?php
class Cotisation
{
	protected $doc;
	protected $callback;
	
	public function __construct($doc, $callback)
	{
		$this->doc = $doc;
		$this->callback = $callback;
	}
	
	public function getDetails($details)
	{
		return $details;
	}
	
}