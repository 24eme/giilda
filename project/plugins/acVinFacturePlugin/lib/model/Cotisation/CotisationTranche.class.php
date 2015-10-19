<?php
class CotisationTranche extends CotisationVariable
{
	protected $tranche;
	protected $depart;
	protected $complement;
	
	public function __construct($template, $document, $datas)
	{
		parent::__construct($template, $document, $datas);
		$this->tranche = $datas->tranche;
		$this->depart = $datas->depart;
		$this->complement = $datas->complement;
	}
	
	public function getQuantite()
	{
		$quantite = (ceil((round($this->getCallbackValue(), self::PRECISION)) / $this->tranche) - $this->depart);
		return ($quantite >= 0)? $quantite : 0;
	}

	public function getTotal()
	{
		return round(($this->prix * $this->getQuantite()) + $this->complement, self::PRECISION);
	}
	
	public function getLibelle()
	{
		return str_replace('%tranche%',$this->tranche, parent::getLibelle());
	}
}