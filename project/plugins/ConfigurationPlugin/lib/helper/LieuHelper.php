<?php

function lieuLibelle($libelles, $format = "%g% %a% %l%") {
	$format_index = array('%c%' => 0,
						  '%g%' => 1,
						  '%a%' => 2,
						  '%l%' => 3);

	$libelle = $format;

	foreach($format_index as $key => $item) {
		$libelle = str_replace($key, $libelles[$item], $libelle);
	}

	return $libelle;
}

function lieuLibelleFromLieu($lieu, $format = "%g% %a% %l%") {

	if ($lieu instanceof sfOutputEscaperIteratorDecorator) {
		$lieu = $lieu->getRawValue();
	}

	if (!($lieu instanceof DRMLieu)) {
		throw new sfRenderException("lieu is not instanceof DRMLieu");
	}

	return lieuLibelle($lieu->getConfig()->getLibelles(), $format);
}