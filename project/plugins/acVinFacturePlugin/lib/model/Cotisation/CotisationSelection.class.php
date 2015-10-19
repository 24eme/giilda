<?php
class CotisationSelection extends Cotisation
{
	
	public function getDetails($details)
	{
		$doc = $this->doc;
		$callback = $this->callback;
		$selections = $doc->$callback();
		$result = array();
		foreach ($selections as $selection) {
			if ($details->exist($selection)) {
				$result[$selection] = $details->get($selection);
			}			
		}
		return $result;
	}
	
}