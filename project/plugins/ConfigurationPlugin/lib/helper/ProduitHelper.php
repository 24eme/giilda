<?php

function produitLibelle($libelles, $libelles_labels = array(), $format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") {
	$format_index = array('%c%' => 0,
						  '%g%' => 1,
						  '%a%' => 2,
						  '%l%' => 3,
						  '%co%' => 4,
						  '%ce%' => 5);

	$libelle = $format;

	foreach($format_index as $key => $item) {
		$libelle = str_replace($key, $libelles[$item], $libelle);
	}

	$libelle = labelsLibelles($libelles_labels, $libelle, $label_separator);

	return $libelle;
}

function labelsLibelles($libelles, $format = "%la%", $label_separator = ", ") {

	return str_replace("%la%", implode($label_separator, $libelles), $format);
}

function produitLibelleFromDetail($detail, $format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") {

	if ($detail instanceof sfOutputEscaperIteratorDecorator) {
		$detail = $detail->getRawValue();
	}

	if (!($detail instanceof DRMDetail)) {
		throw new sfRenderException("detail is not instanceof DRMDetail");
	}
	return produitLibelle($detail->getLibelles(), $detail->getLabelLibelles(), $format, $label_separator);
}