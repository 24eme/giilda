<?php

class SubventionConfiguration {

    private static $_instance = null;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new SubventionConfiguration();
        }
        return self::$_instance;
    }

    public function __construct() {

    }

    public function getInfosSchema($operation) {
        return array(
            "economique" => array(
                                "capital_social" => array("label" => "Capital Social", "unite" => "€"),
                                "chiffre_affaire" => array("label" => "Chiffre d'affaire", "unite" => "€"),
                                "effectif" => array("label" => "Effectif", "unite" => "ETP")
                            ),
            "produits" => array(
                                "gammes" => array("libelle" => array("label" => "Gamme de produit"),
                                                  "volume" => array("label" => "Volume", "unite" => "hl")),
                                "part_vins_occitans" => array("label" => "Part de vins occitans", "unite" => "%")
                            ),
            "contacts" => array("nom" => array(), "email" => array()),
        );
    }

}
