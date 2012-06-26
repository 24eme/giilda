<?php

function isRectifierCssClass($object, $key) {

	if (isRectifier($object, $key)) {

		return rectifierCssClass();
	} else {

		return null;
	}
}

function rectifierCssClass() {

	return 'rectifier';
}

function isRectifier($object, $key) {

	return $object->getDocument()->isModifiedMasterDRM($object->getHash(), $key);
}