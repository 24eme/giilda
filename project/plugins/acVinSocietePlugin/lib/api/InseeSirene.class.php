<?php

class InseeSirene
{
    public static function getJson($sirenOrSiret)
    {
        if(strlen($sirenOrSiret) == 14) {
            $json = json_decode(file_get_contents("https://api-avis-situation-sirene.insee.fr/identification/siret/".$sirenOrSiret));
        }

        if(!$json && strlen($sirenOrSiret) == 14) {
            $sirenOrSiret = substr($sirenOrSiret, 0, 9);
        }

        if(strlen($sirenOrSiret) == 9) {
            $json = json_decode(file_get_contents("https://api-avis-situation-sirene.insee.fr/identification/siren/".$sirenOrSiret));
        }

        if(!isset($json->etablissements[0])) {

            return null;
        }

        $json->etablissements[0]->tva = self::calculTVA($json->etablissements[0]->siret);

        return $json->etablissements[0];
    }

    public static function calculTVA($sirenOrSiret, $codePays = "FR") {
        if(!preg_match("/^([0-9]{9}|[0-9]{14})$/", $sirenOrSiret)) {
            return;
        }
        if($codePays != "FR") {
            return;
        }
        $siren = substr($sirenOrSiret, 0, 9);
        $cleTVA = (12 + (3 * (intval($siren) % 97))) % 97;

        return sprintf("%s%02d%09d", $codePays, $cleTVA, $siren);
    }

}
