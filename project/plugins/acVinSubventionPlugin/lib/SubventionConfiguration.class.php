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

    public function getEngagementsPrecisions() {
        return (isset($this->configuration['engagements_precisions']))? $this->configuration['engagements_precisions'] : array();
    }

    public function getEngagementLibelle($key) {
        $engagements = $this->getEngagements();
        return (isset($engagements[$key]))? $engagements[$key] : '';
    }

    public function getEngagementPrecisionLibelle($key, $skey) {
        $precisions = $this->getEngagementsPrecisions();
        return (isset($precisions[$key]) && isset($precisions[$key][$skey]))? $precisions[$key][$skey] : '';
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

    public function isActif() {
        return $this->configuration['actif'];
    }

}
