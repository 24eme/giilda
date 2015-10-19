<?php
class CotisationVariable extends CotisationFixe
{
	
	public function getQuantite()
	{
		$quantite = round($this->getCallbackValue(), self::PRECISION);
		
		return ($quantite >= 0) ? $quantite : 0;
	}
	
	public function getLibelle()
	{
		return str_replace('%callback%', $this->getCallbackValue(), parent::getLibelle());
	}
	
	public function getCallbackValue()
	{
		$document = $this->document;
		$callback = $this->callback;
		return round($document->$callback(), self::PRECISION);
	}
	
}