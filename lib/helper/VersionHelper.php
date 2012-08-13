<?php

function isVersionCssClass($object, $key) {

    if (isVersion($object, $key)) {

        return versionCssClass();
    } else {

        return null;
    }
}

function versionCssClass() {

    return 'version';
}

function isVersion($object, $key) {

    return $object->getDocument()->isModifiedMother($object->getHash(), $key);
}