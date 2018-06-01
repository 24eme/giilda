<?php

class DRMCsvEdi extends CsvFile {

    public $erreurs = array();
    public $statut = null;
    public $countryList = array();

    public static $countryTermsList = array('HK' => array('Hong Kong'));

    const STATUT_ERREUR = 'ERREUR';
    const STATUT_ERROR = 'ERROR';
    const STATUT_VALIDE = 'VALIDE';
    const STATUT_WARNING = 'WARNING';
    const TYPE_CAVE = 'CAVE';
    const TYPE_CRD = 'CRD';
    const TYPE_ANNEXE = 'ANNEXE';
    const TYPE_ANNEXE_NONAPUREMENT = 'NONAPUREMENT';
    const TYPE_ANNEXE_SUCRE = 'SUCRE';
    const TYPE_ANNEXE_OBSERVATIONS = 'OBSERVATIONS';
    const CSV_TYPE = 0;
    const CSV_PERIODE = 1;
    const CSV_IDENTIFIANT = 2;
    const CSV_NUMACCISE = 3;
    const CSV_CAVE_CERTIFICATION = 4;
    const CSV_CAVE_GENRE = 5;
    const CSV_CAVE_APPELLATION = 6;
    const CSV_CAVE_MENTION = 7;
    const CSV_CAVE_LIEU = 8;
    const CSV_CAVE_COULEUR = 9;
    const CSV_CAVE_CEPAGE = 10;
    const CSV_CAVE_COMPLEMENT = 11;
    const CSV_CAVE_LIBELLE_COMPLET = 12;
    const CSV_CAVE_TYPE_DRM = 13;

    const CSV_CAVE_CATEGORIE_MOUVEMENT = 14;
    const CSV_CAVE_TYPE_MOUVEMENT = 15;
    const CSV_CAVE_VOLUME = 16;
    const CSV_CAVE_EXPORTPAYS = 17;
    const CSV_CAVE_CONTRATID = 18;

    const CSV_CAVE_COMPLEMENT_PRODUIT = 14;
    const CSV_CAVE_TYPE_COMPLEMENT_PRODUIT = 15;
    const CSV_CAVE_VALEUR_COMPLEMENT_PRODUIT = 16;

    const CSV_CRD_COULEUR = 4;
    const CSV_CRD_GENRE = 5;
    const CSV_CRD_CENTILITRAGE = 6;
    const CSV_CRD_REGIME = 13;
    const CSV_CRD_CATEGORIE_KEY = 14;
    const CSV_CRD_TYPE_KEY = 15;
    const CSV_CRD_QUANTITE = 16;

    const CSV_ANNEXE_TYPEANNEXE = 14;
    const CSV_ANNEXE_TYPEMVT = 15;
    const CSV_ANNEXE_QUANTITE = 16;
    const CSV_ANNEXE_NONAPUREMENTDATEEMISSION = 17;
    const CSV_ANNEXE_NONAPUREMENTACCISEDEST = 18;
    const CSV_ANNEXE_NUMERODOCUMENT = 19;
    const CSV_ANNEXE_OBSERVATION = 17;

    const COMPLEMENT = "COMPLEMENT";
    const COMPLEMENT_OBSERVATIONS = "OBSERVATIONS";
    const COMPLEMENT_TAV = "TAV";
    const COMPLEMENT_PREMIX = "PREMIX";

    protected static $permitted_types = array(self::TYPE_CAVE,
        self::TYPE_CRD,
        self::TYPE_ANNEXE);
    protected static $permitted_annexes_type_mouvements = array('DEBUT', 'FIN');
    protected $drm = null;
    protected $csv = null;

    protected static $genres = array('MOU' => 'Mousseux', 'EFF' => 'Mousseux', 'TRANQ' => 'Tranquille','DEFAUT' => 'Tranquille','VCI' => 'VCI');
    protected static $stocks_non_additionnables = array("stock_debut","stock_fin","stocks_debut","stocks_fin");
    protected static $genres_synonyme = array('FINESBULLES' => 'Mousseux',
                                              'FINES-BULLES' => 'Mousseux',
                                              'EFFERVESCENT' => 'Mousseux',
                                              'MOUSSEUX' => 'Mousseux',
                                              'MOU' => 'Mousseux',
                                              'EFF' => 'Mousseux',
                                              'TRANQ' => 'Tranquille',
                                              'TRANQUILLE' => 'Tranquille',
                                              'DEFAUT' => 'Tranquille');
    protected $type_annexes = array(self::TYPE_ANNEXE_NONAPUREMENT => 'Non Apurement', self::TYPE_ANNEXE_SUCRE => 'Sucre', self::TYPE_ANNEXE_OBSERVATIONS => 'Observations');
    protected static  $cat_crd_mvts = array("stock_debut","entrees","sorties","stock_fin");
    protected static  $type_crd_mvts = array("achats","retours","excedents","utilisations","destructions","manquants","fin","debut");
    protected static  $types_complement = array(self::COMPLEMENT_OBSERVATIONS, self::COMPLEMENT_TAV, self::COMPLEMENT_PREMIX);

    protected static $regimes_crd = array("PERSONNALISE" => EtablissementClient::REGIME_CRD_PERSONNALISE,
                                          "PERSONNALISES" => EtablissementClient::REGIME_CRD_PERSONNALISE,
                                          "COLLECTIVE-ACQUITTE" => EtablissementClient::REGIME_CRD_COLLECTIF_ACQUITTE,
                                          "COLLECTIVES-ACQUITTES" => EtablissementClient::REGIME_CRD_COLLECTIF_ACQUITTE,
                                          "COLLECTIVE-SUSPENDU" => EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU,
                                          "COLLECTIVES-SUSPENDUS" => EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU);

    public function __construct($file, DRM $drm = null) {
        $this->drm = $drm;
        $this->type_annexes_docs = array_merge($this->type_annexes, DRMClient::$drm_documents_daccompagnement);
        $this->buildCountryList();
        parent::__construct($file);
    }

    public function buildCountryList() {
        $countryList = ConfigurationClient::getInstance()->getCountryList();

        $match_array = array();
        foreach ($countryList as $keyUpper => $countryString) {
            $match_array[$keyUpper] = $countryString;
            $match_array[$countryString] = $countryString;
        }
        $this->countryList = array_merge($countryList, $match_array);
    }

    public function findPays($pays){
      foreach($this->countryList as $countryKey => $country){
        if(KeyInflector::slugify($country) == KeyInflector::slugify($pays) || KeyInflector::slugify($countryKey) == KeyInflector::slugify($pays)) {
          return $countryKey;
        }
      }
      foreach (self::$countryTermsList as $countryKey => $countriesSynArr) {
         foreach ($countriesSynArr as $countrieSyn) {
             if(KeyInflector::slugify($countrieSyn) == KeyInflector::slugify($pays)) {
               return $countryKey;
             }
         }
      }
      return false;
    }

}
