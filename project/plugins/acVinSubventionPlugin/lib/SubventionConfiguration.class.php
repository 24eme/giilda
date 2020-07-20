<?php

class SubventionConfiguration {

    private static $_instance = null;
    protected $configuration;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new SubventionConfiguration();
        }
        return self::$_instance;
    }

    public function __construct() {
        if(!sfConfig::has('subvention_configuration_subvention')) {
          throw new sfException("La configuration pour les fichiers n'a pas été défini pour cette application");
        }

        $this->configuration = sfConfig::get('subvention_configuration_subvention', array());
    }

    public function getInfosSchema($operation) {
        return array(
            "economique" => array(
                                "capital_social" => array("label" => "Capital Social", "type" => "float"),
                                "etp" => array("label" => "Nombre d'ETP", "type" => "float"),
                                "effectif_permanent" => array("label" => "Dont effectif permanent", "type" => "float"),
                            ),
            "economique_libelle" => "Données économiques",
            "contacts" => array("nom" => array("label" => "Prénom Nom"), "email" => array("label" => "Email"), "telephone" => array("label" => "Téléphone")),
            "contacts_libelle" => "Contacts de la personne en charge du dossier au sein de l’entreprise",

        );
    }

    public function isActif() {
        return $this->configuration['actif'];
    }

}
