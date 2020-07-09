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
                                "etp" => array("label" => "ETP"),
                                "effectif_permanent" => array("label" => "Dont effectif permanent"),
                            ),
            "economique_libelle" => "Données économiques",
            "contacts" => array("nom" => array(), "email" => array(), "telephone" => array()),
            "contacts_libelle" => "Contacts de la personne en charge du dossier au sein de l’entreprise",

        );
    }

}
