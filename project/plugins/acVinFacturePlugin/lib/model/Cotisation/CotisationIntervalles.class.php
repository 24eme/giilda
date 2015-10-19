<?php
class CotisationIntervalles extends CotisationVariable
{
	protected $intervalles;
	
	public function __construct($template, $document, $datas)
	{
		parent::__construct($template, $document, $datas);
		$this->intervalles = $datas->intervalles;
	}
	
	public function getTotal()
	{
		$total = 0;
		$quantite = $this->getQuantite();
		foreach ($this->intervalles as $intervalle => $prix) {
			if ($quantite <= $intervalle) {
				if ($variable = $prix->variable) {
					$total = $quantite * $variable;
				} else {
					$total = $prix->prix;
				}
				break;
			}
		}
		return round($total, self::PRECISION);
	}
}