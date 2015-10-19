<?php
class CotisationFacture extends CotisationVariable
{
	protected $minimum;
	protected $complement;
	
	public function __construct($template, $document, $datas)
	{
		parent::__construct($template, $document, $datas);
		$this->minimum = round($datas->minimum, self::PRECISION);
		$this->complement = round($datas->complement, self::PRECISION);
	}
	
	public function getTotal()
	{
		$total = round($this->prix * $this->getQuantite(), self::PRECISION);
		$total = ($this->minimum && $this->minimum > $total)? $this->minimum : $total;
		return round($total + $this->complement, self::PRECISION);
	}
	
	public function getCallbackValue()
	{
		$hash = $this->callback;
		$value = 0;
		if ($this->template->exist($hash)) {
			$datas = $this->template->get($hash);
			$value = $datas->getTotal($this->document);
		}
		return round($value, self::PRECISION);
	}
	
}