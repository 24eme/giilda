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
			throw new sfException("La configuration pour les subventions n'a pas été définie pour cette application");
		}

        $this->configuration = sfConfig::get('subvention_configuration_subvention', array());
    }

    public function getEngagements() {
        return (isset($this->configuration['engagements']))? $this->configuration['engagements'] : array();
    }


    public function getEngagementLibelle($key) {
        $engagements = $this->getEngagements();
        return (isset($engagements[$key]))? $engagements[$key] : '';
    }

    public function getPlateforme() {
        return (isset($this->configuration['plateforme']))? $this->configuration['plateforme'] : array();
    }

    public function getReferent() {
        return (isset($this->configuration['referent']))? $this->configuration['referent'] : array();
    }

    public function getInfosSchema($operation) {
        return array(
            "economique" => array(
                                "capital_social" => array("label" => "Capital Social", "type" => "float", "unite" => "€"),
                                "effectif" => array("label" => "Effectif", "type" => "float", "unite" => "ETP", "help" => "Équivalent temps plein"),
                                "effectif_permanent" => array("label" => "Dont effectif permanent", "type" => "float", "unite" => "ETP", "help" => "Équivalent temps plein"),
                            ),
            "economique_libelle" => "Données économiques",
            "contacts" => array("nom" => array("label" => "Prénom Nom"), "email" => array("label" => "Email"), "telephone" => array("label" => "Téléphone")),
            "contacts_libelle" => "Contacts de la personne en charge du dossier au sein de l’entreprise",

        );
    }

    public function getApprobationsSchema($operation) {
        return array(
            "viticulteur" => array(
                                "hve" => array("label" => "HVE III", "type" => "float", "unite" => "hl", "help" => "Contractualisation HVE III"),
                                "mae" => array("label" => "MAE", "type" => "float", "unite" => "hl", "help" => "Contractualisation MAE")
                            ),
            "viticulteur_libelle" => "Approbations pour un viticulteur",
            "negociant" => array(
                                 "contractualisation_annuel_effective" => array("label" => "Contractualisation annuel effective", "type" => "float", "unite" => "hl"),
                                 "contractualisation_annuel_engagement" => array("label" => "Contractualisation annuel engagement", "type" => "float", "unite" => "hl"),
                                 "contractualisation_pluriannuel_effective" => array("label" => "Contractualisation pluriannuel effective", "type" => "float", "unite" => "hl"),
                                 "contractualisation_pluriannuel_engagement" => array("label" => "Contractualisation pluriannuel engagement", "type" => "float", "unite" => "hl"),
                                ),
            "negociant_libelle" => "Approbations pour un négociant",
        );
    }



    public function isActif() {
        return $this->configuration['actif'];
    }

    public function getOperationEnCours() {
        return $this->configuration['operation_en_cours'];
    }

}
