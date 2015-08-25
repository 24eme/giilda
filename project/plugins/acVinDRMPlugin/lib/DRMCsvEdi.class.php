<?php

class DRMCsvEdi extends CsvFile {

    public $erreurs = array();
    public $statut = null;

    const STATUT_ERREUR = 'ERREUR';
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
    const CSV_NUMACCISE = 2;
    const CSV_CAVE_CERTIFICATION = 3;
    const CSV_CAVE_GENRE = 4;
    const CSV_CAVE_APPELLATION = 5;
    const CSV_CAVE_MENTION = 6;
    const CSV_CAVE_LIEU = 7;
    const CSV_CAVE_COULEUR = 8;
    const CSV_CAVE_CEPAGE = 9;
    const CSV_CAVE_CATEGORIE_MOUVEMENT = 10;
    const CSV_CAVE_TYPE_MOUVEMENT = 11;
    const CSV_CAVE_VOLUME = 12;
    const CSV_CAVE_COMPLEMENT = 13;
    const CSV_CRD_GENRE = 3;
    const CSV_CRD_COULEUR = 4;
    const CSV_CRD_CENTILITRAGE = 5;
    const CSV_CRD_QUANTITE_KEY = 11;
    const CSV_CRD_QUANTITE = 12;
     const CSV_ANNEXE_TYPEANNEXE = 3;
     const CSV_ANNEXE_IDDOC = 4;
     const CSV_ANNEXE_TYPEMVT_ACCISE = 11;
     const CSV_ANNEXE_QUANTITE = 12;
     const CSV_ANNEXE_COMPLEMENT = 13;

    protected static $permitted_types = array(self::TYPE_CAVE,
        self::TYPE_CRD,
        self::TYPE_ANNEXE);
    protected $drm = null;
    protected $csv = null;
    protected static $genres = array('MOU' => 'Mousseux', 'EFF' => 'Effervescent', 'TRANQ' => 'Tranquille');
    protected $type_annexes = array(self::TYPE_ANNEXE_NONAPUREMENT => 'Non Apurement',self::TYPE_ANNEXE_SUCRE => 'Sucre', self::TYPE_ANNEXE_OBSERVATIONS => 'Observations');


    public function __construct($file,DRM $drm = null) {
        $this->drm = $drm;
        $this->type_annexes_docs = array_merge($this->type_annexes,  DRMClient::$drm_documents_daccompagnement);
        parent::__construct($file);
    }      
}
