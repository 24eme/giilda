<?php
class EtablissementFamilles
{

    const FAMILLE_PRODUCTEUR = "PRODUCTEUR";
    const FAMILLE_PRODUCTEUR_VINIFICATEUR = "PRODUCTEUR_VINIFICATEUR";
    const FAMILLE_NEGOCIANT = "NEGOCIANT";
    const FAMILLE_NEGOCIANT_VINIFICATEUR = "NEGOCIANT";
    const FAMILLE_NEGOCIANT_PUR = "NEGOCIANT_PUR";
    const FAMILLE_COOPERATIVE = "COOPERATIVE";
    const FAMILLE_COURTIER = "COURTIER";
    const FAMILLE_REPRESENTANT = "REPRESENTANT";

    public static $familles = array(
    	self::FAMILLE_PRODUCTEUR => "Producteur",
        self::FAMILLE_PRODUCTEUR_VINIFICATEUR => "Producteur Vinificateur",
    	self::FAMILLE_NEGOCIANT => "Négociant",
    	self::FAMILLE_COOPERATIVE => "Coopérative",
    	self::FAMILLE_COURTIER => "Courtier",
		self::FAMILLE_REPRESENTANT => "Representant",
    	self::FAMILLE_NEGOCIANT_PUR => "Négociant Pur",
    );

    protected static $type_societe_famille = array(
        SocieteClient::TYPE_OPERATEUR => array(self::FAMILLE_PRODUCTEUR, self::FAMILLE_PRODUCTEUR_VINIFICATEUR, self::FAMILLE_NEGOCIANT, self::FAMILLE_COOPERATIVE, self::FAMILLE_REPRESENTANT, self::FAMILLE_NEGOCIANT_PUR),
        SocieteClient::TYPE_COURTIER => array(self::FAMILLE_COURTIER),
        SocieteClient::TYPE_AUTRE => array(),
    );

    public static function getFamilles()
    {
    	return self::$familles;
    }

    public static function getFamilleLibelle($famille = null)
    {
        $familles = self::getFamilles();
    	$famille = str_replace('-', '_', strtoupper(KeyInflector::slugify($famille)));
    	if (!in_array($famille, array_keys($familles))) {
    		throw new sfException('La clé famille "'.$famille.'" n\'existe pas');
    	}
    	return $familles[$famille];
    }

}
