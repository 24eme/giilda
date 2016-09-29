<?php

function getPointAideText($categorie, $idPointAide) {
    return PointsAidesConfiguration::getInstance()->getPointAide($categorie, $idPointAide);
}

function getPointAideHtml($categorie, $idPointAide) {
    $text = getPointAideText($categorie, $idPointAide);
    $hmtl = '&nbsp;<span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" data-toggle="tooltip" title="'.$text.'"></span>';
    return $hmtl;
}
