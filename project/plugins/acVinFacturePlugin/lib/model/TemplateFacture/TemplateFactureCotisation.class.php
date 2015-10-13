<?php
/**
 * Model for TemplateFactureCotisation
 *
 */

class TemplateFactureCotisation extends BaseTemplateFactureCotisation {

	public function getTotal($document)
	{
		$total = 0;
		foreach ($this->details as $type => $detail) {
			$total += $detail->getTotal($document);
		}
		return $total;
	}
}