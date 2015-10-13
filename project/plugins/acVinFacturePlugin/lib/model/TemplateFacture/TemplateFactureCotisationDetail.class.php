<?php
/**
 * Model for TemplateFactureCotisationDetail
 *
 */

class TemplateFactureCotisationDetail extends BaseTemplateFactureCotisationDetail {

	public function getTotal($document)
	{
		$docs = $this->docs->toArray();
		$total = 0;
		if (in_array($document->type, $docs)) {
			$modele = $this->modele;
			$object = new $modele($this->getDocument(), $document, $this);
			$total = $object->getTotal();
		}
		return $total;
	}
}