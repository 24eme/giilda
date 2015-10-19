<?php
class CotisationFixe extends CotisationBase
{
	public function getTotal()
	{
		return round($this->prix * $this->getQuantite(), self::PRECISION);
	}

	public function getQuantite() {
		$callback = $this->callback;

		if($callback && round($this->document->$callback(), self::PRECISION) <= 0) {

			return 0;
		}

		return parent::getQuantite();
	}
}