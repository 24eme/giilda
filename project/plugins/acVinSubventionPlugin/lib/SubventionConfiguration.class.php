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
            "economique" => array("capital_social", "chiffre_affaire", "effectif_etp"),
            "produits" => array("*", "%  de vins coctitants"),
            "contacts" => array("nom", "email"),
        );
    }

}
