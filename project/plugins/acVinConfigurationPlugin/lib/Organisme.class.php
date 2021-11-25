<?php

class Organisme
{
    private static $_instance = null;
	protected $configuration;

	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
		if(!sfConfig::has('app_configuration_facture')) {
			throw new sfException("Organisme config not found in app.yml");
		}
		$this->configuration = sfConfig::get('app_configuration_facture', array());
	}

    public function getInfo($node, $key)
    {
        if(!isset($this->configuration[$node])) {
            return null;
        }
        if(!isset($this->configuration[$node][$key])) {
            return null;
        }
        return $this->configuration[$node][$key];
    }

    public function getAdresse()
    {
        return $this->getInfo('emetteur_cvo', 'adresse');
    }

    public function getCodePostal()
    {
        return $this->getInfo('emetteur_cvo', 'code_postal');
    }

    public function getCommune()
    {
        return $this->getInfo('emetteur_cvo', 'ville');
    }

    public function getServiceFacturation()
    {
        return $this->getInfo('emetteur_cvo', 'service_facturation');
    }

    public function getTelephone()
    {
        return $this->getInfo('emetteur_cvo', 'telephone');
    }

    public function getEmail()
    {
        return $this->getInfo('emetteur_cvo', 'email');
    }

    public function getBanque()
    {
        return $this->getInfo('coordonnees_bancaire', 'banque');
    }

    public function getBic()
    {
        return $this->getInfo('coordonnees_bancaire', 'bic');
    }

    public function getCreditorId()
    {
        return $this->getInfo('coordonnees_bancaire', 'creditor_id');
    }

    public function getIban()
    {
        return $this->getInfo('coordonnees_bancaire', 'iban');
    }

    public function getNoTvaIntracommunautaire()
    {
        return $this->getInfo('infos_interpro', 'tva_intracom');
    }

    public function getSiret()
    {
        return $this->getInfo('infos_interpro', 'siret');
    }

    public function getApe()
    {
        return $this->getInfo('infos_interpro', 'ape');
    }

    public function getNom()
    {
        return $this->getInfo('infos_interpro', 'nom');
    }
}
