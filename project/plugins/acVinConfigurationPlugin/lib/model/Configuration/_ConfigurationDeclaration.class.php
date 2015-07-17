<?php

/**
 * Inheritance tree class _ConfigurationDeclaration
 *
 */
abstract class _ConfigurationDeclaration extends acCouchdbDocumentTree {

    protected $libelles = null;
    protected $codes = null;
    protected $produits_all = null;
    protected $produits = array();
    protected $libelle_format = array();

    protected function loadAllData() {
        parent::loadAllData();
        $this->getProduitsAll();
        $this->loadProduitsByDates();
        $this->getLibelles();
        $this->getCodes();
    }

    abstract public function getChildrenNode();

    public function getParentNode() {
        $parent = $this->getParent()->getParent();
        if (!$parent instanceof _ConfigurationDeclaration) {

            throw new sfException('Noeud racine atteint');
        } else {

            return $this->getParent()->getParent();
        }
    }

    public function getDatesDroits($interpro) {
        $dates_droits = array();

        $noeudDroits = $this->getDroits($interpro);
        if($noeudDroits) {
            foreach($noeudDroits as $droits) {
                foreach($droits as $droit) {
                    $dateObj = new DateTime($droit->date);
                    $dates_droits[$dateObj->format('Y-m-d')] = true;
                }
            }
        }

        krsort($dates_droits);

        if(!$this->getChildrenNode()) {

            return $dates_droits;
        }

        foreach($this->getChildrenNode() as $child) {
            $dates_droits = array_merge($dates_droits, $child->getDatesDroits($interpro));
        }

        krsort($dates_droits);

        return $dates_droits;
    }

    public function getProduitsAll($interpro = null, $departement = null) {
        if (is_null($this->produits_all)) {
            $this->produits_all = array();
            foreach ($this->getChildrenNode() as $key => $item) {
                $this->produits_all = array_merge($this->produits_all, $item->getProduitsAll());
            }
        }

        return $this->produits_all;
    }

    public function findDroitsDate($date, $interpro) {
        $datesDroits = $this->getDatesDroits($interpro);

        foreach($datesDroits as $dateDroits => $null) {
            if($date >= $dateDroits) {

                return $dateDroits;
            }
        }

        throw new sfExcetion("Date introuvable");
    }


    public function getKeyDroits($droits) {
        sort($droits);

        return implode("", $droits);
    }

    public function loadProduitsByDates($interpro = "INTERPRO-inter-loire") {
        $datesDroits = $this->getDatesDroits($interpro);
        $droitsCombinaison = array(array(ConfigurationDroits::DROIT_CVO), array(ConfigurationDroits::DROIT_DOUANE), array(ConfigurationDroits::DROIT_CVO, ConfigurationDroits::DROIT_DOUANE));
        foreach($datesDroits as $dateDroit => $null) {
            foreach($droitsCombinaison as $droits) {
                $this->getProduits($dateDroit, $interpro, null, $droits);
            }
        }
    }

    public function getProduits($date = null, $interpro = "INTERPRO-inter-loire", $departement = null, $droits = array(ConfigurationDroits::DROIT_CVO)) {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $date = $this->findDroitsDate($date, $interpro);
        $droitsKey = $this->getKeyDroits($droits);

        if(array_key_exists($date, $this->produits) && array_key_exists($droitsKey, $this->produits[$date])) {

            return $this->produits[$date][$droitsKey];
        }

        $produits = array();

        foreach ($this->getProduitsAll($interpro, $departement) as $hash => $item) {
            if(!count($droits)) {
                $produits[$hash] = $item;
                continue;
            }

            if (in_array(ConfigurationDroits::DROIT_CVO, $droits) && $item->hasCVO($date)) {
                $produits[$hash] = $item;
                continue;
            }

            if (in_array(ConfigurationDroits::DROIT_DOUANE, $droits) && $item->hasDouane($date)) {
                $produits[$hash] = $item;
                continue;
            }
        }

        if(!array_key_exists($date, $this->produits)) {

            $this->produits[$date] = array();
        }

        $this->produits[$date][$droitsKey] = $produits;

        return $this->produits[$date][$droitsKey];
    }

    public function getProduitsAuto($date = null, $interpro = null, $departement = null, $droits = array(ConfigurationDroits::DROIT_CVO)) {
        $produits = $this->getProduits($date, $interpro, $departement, $droits);
        $produits_auto = array();
        
        foreach($produits as $hash => $produit) {
            if(preg_match("/AUTRES/", $hash)) {
                $produits_auto[$hash] = $produit;
            }
        }

        return $produits_auto;
    }

    public function hasCVO($date) {
        try {
            $droit_produit = $this->getDroitCVO($date);
            $cvo_produit = $droit_produit->getTaux();
        } catch (Exception $ex) {
            $cvo_produit = 0;
        }

        return $cvo_produit >= 0;
    }

    public function hasDouane($date) {
        try {
            $droit_produit = $this->getDroitDouane($date);
            $douane_produit = $droit_produit->getTaux();
        } catch (Exception $ex) {
            $douane_produit = 0;
        }

        return $douane_produit >= 0;
    }

    public function getLibelles() {
        if (is_null($this->libelles)) {
            $this->libelles = array_merge($this->getParentNode()->getLibelles(), array($this->libelle));
        }

        return $this->libelles;
    }

    public function getCodes() {
        if (is_null($this->codes)) {
            $this->codes = array_merge($this->getParentNode()->getCodes(), array($this->code));
        }

        return $this->codes;
    }

    public function getProduitsHashByCodeDouane($date, $interpro, $droits = array()) {
        $produits = array();
        foreach ($this->getProduits($date, $interpro, $droits) as $hash => $item) {
            $produits[$item->getCodeDouane()] = $hash;
        }

        return $produits;
    }

    public function getCodeDouane() {
        if (!$this->_get('code_douane')) {

            return $this->getParentNode()->getCodeDouane();
        }

        return $this->_get('code_douane');
    }

    public function getCodeProduit() {
        if (!$this->_get('code_produit')) {

            return $this->getParentNode()->getCodeProduit();
        }

        return $this->_get('code_produit');
    }

    public function getCodeComptable() {
        if (!$this->_get('code_comptable')) {

            return $this->getParentNode()->getCodeComptable();
        }

        return $this->_get('code_comptable');
    }

    public function getFormatLibelleCalcule() {
        if (!$this->getFormatLibelle()) {

            return $this->getParentNode()->getFormatLibelleCalcule();
        }

        return $this->getFormatLibelle();
    }

    public function getFormatLibelleDefinitionNoeud() {
        if ($this->getFormatLibelle()) {

            return $this;
        }

        return $this->getParentNode()->getFormatLibelleDefinitionNoeud();
    }

    public function getDensite() {
        if (!$this->exist('densite') || !$this->_get('densite')) {
            try {

                return $this->getParentNode()->getDensite();
            } catch (Exception $e) {

                return null;
            }
        }

        return $this->_get('densite');
    }

    public function getLibelleFormat($labels = array(), $format = "%format_libelle%", $label_separator = ", ") {
        if (!array_key_exists($format, $this->libelle_format)) {
            $format_libelle = $this->getFormatLibelleCalcule();
            $format = str_replace("%format_libelle%", $format_libelle, $format);
            $libelle = $this->formatProduitLibelle($format);
            $libelle = $this->getDocument()->formatLabelsLibelle($labels, $libelle, $label_separator);
            $this->libelle_format[$format] = trim($libelle);
        }

        return $this->libelle_format[$format];
    }

    public function formatProduitLibelle($format = "%g% %a% %m% %l% %co% %ce%") {
        $libelle = ConfigurationClient::getInstance()->formatLibelles($this->getLibelles(), $format);

        $libelle = str_replace(array('%code%',
            '%code_produit%',
            '%code_comptable%'), array($this->getCodeFormat(),
            $this->getCodeProduit(),
            $this->getCodeComptable()), $libelle);
        $libelle = str_replace("()", "", $libelle);
        $libelle = preg_replace('/ +/', ' ', $libelle);


        return $libelle;
    }

    public function getCodeFormat($format = "%g%%a%%m%%l%%co%%ce%") {

        return ConfigurationClient::getInstance()->formatCodes($this->getCodes(), $format);
    }

    public function getDroitCVO($date, $interpro = "INTERPRO-inter-loire") {

        return $this->getDroits($interpro)->get(ConfigurationDroits::CODE_CVO)->getCurrentDroit($date);
    }

    public function getDroits($interpro) {
        $droitsable = $this;
        while (!$droitsable->hasDroits()) {
            $droitsable = $droitsable->getParent()->getParent();
        }
        return $droitsable->interpro->getOrAdd($interpro)->droits;
    }

    public function getDateCirulation($campagne, $interpro = "INTERPRO-inter-loire") {
        $dateCirculationAble = $this;
        while (!$dateCirculationAble->exist('interpro') ||
        !$dateCirculationAble->interpro->getOrAdd($interpro)->exist('dates_circulation') ||
        !count($dateCirculationAble->interpro->getOrAdd($interpro)->dates_circulation) ||
        !$dateCirculationAble->interpro->getOrAdd($interpro)->dates_circulation->exist($campagne)) {
            $dateCirculationAble = $dateCirculationAble->getParent()->getParent();
        }
        if (!$dateCirculationAble->exist('interpro') ||
                !$dateCirculationAble->interpro->getOrAdd($interpro)->exist('dates_circulation') ||
                !count($dateCirculationAble->interpro->getOrAdd($interpro)->dates_circulation) ||
                !$dateCirculationAble->interpro->getOrAdd($interpro)->dates_circulation->exist($campagne)) {
            return null;
        }
        return $dateCirculationAble->interpro->getOrAdd($interpro)->dates_circulation->get($campagne);
    }

    public function setLabelCsv($datas) {
        $labels = $this->interpro->getOrAdd('INTERPRO-' . strtolower($datas[LabelCsvFile::CSV_LABEL_INTERPRO]))->labels;
        $canInsert = true;
        foreach ($labels as $label) {
            if ($label == $datas[LabelCsvFile::CSV_LABEL_CODE]) {
                $canInsert = false;
                break;
            }
        }
        if ($canInsert) {
            $labels->add(null, $datas[LabelCsvFile::CSV_LABEL_CODE]);
        }
    }

    protected function setDepartementCsv($datas) {
        if (!array_key_exists(ProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS, $datas) || !$datas[ProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS]) {

            $this->departements = array();

            return;
        }

        $this->departements = explode(',', $datas[ProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS]);
    }

    protected function setDroitDouaneCsv($datas, $code_applicatif) {

        if (!array_key_exists(ProduitCsvFile::CSV_PRODUIT_DOUANE_NOEUD, $datas) || $code_applicatif != $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_NOEUD]) {

            return;
        }

        $droits = $this->getDroits('INTERPRO-' . strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
        $date = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE]) ? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE] : '1900-01-01';
        $taux = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_TAXE]) ? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_TAXE]) : 0;
        $code = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_CODE]) ? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_CODE] : null;
        $libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_LIBELLE]) ? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_LIBELLE] : null;
        $canInsert = true;
        foreach ($droits->douane as $droit) {
            $dateExistante = new DateTime($droit->date);
            if ($dateExistante->format('Y-m-d') == $date && $droit->taux === $taux && $droit->code == $code) {
                $canInsert = false;
                break;
            }
        }
        if ($canInsert) {
            $droits = $droits->douane->add();
            $droits->date = $date;
            $droits->taux = $taux;
            $droits->code = $code;
            $droits->libelle = $libelle;
        }
    }

    protected function setDroitCvoCsv($datas, $code_applicatif) {

        if (!isset($datas[ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD]) || $code_applicatif != $datas[ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD]) {

            return;
        }

        $droits = $this->getDroits('INTERPRO-' . strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
        $date = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE]) ? $datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE] : '1900-01-01';
        $taux = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE]) ? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE]) : 0;
        $code = ConfigurationDroits::CODE_CVO;
        $libelle = ConfigurationDroits::LIBELLE_CVO;
        $canInsert = true;
        foreach ($droits->cvo as $droit) {
            $dateExistante = new DateTime($droit->date);
            if ($dateExistante->format('Y-m-d') == $date && $droit->code == $code) {
                $canInsert = false;
                break;
            }
        }
        if ($canInsert) {
            $droits = $droits->cvo->add();
            $droits->date = $date;
            $droits->taux = $taux;
            $droits->code = $code;
            $droits->libelle = $libelle;
        }
    }

    protected function castFloat($float) {
        return floatval(str_replace(',', '.', $float));
    }

    public function formatProduits($date = null, $interpro = null, $departement = null, $format = "%format_libelle% (%code_produit%)", $droits = array()) {
        if (!$date) {
            $date = date('Y-d-m');
        }

        $produits = $this->getProduits($date, $interpro, $departement, $droits);
        $produits_formated = array();
        foreach ($produits as $hash => $produit) {
            $produits_formated[$hash] = $produit->getLibelleFormat(array(), $format, ',');
        }
        return $produits_formated;
    }

    public function getLabels($interpro) {

        throw new sfException("The method \"getLabels\" is not defined");
    }

    public function setDonneesCsv($datas) {
        if ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_PRODUIT_NOEUD] == $this->getTypeNoeud()) {
            $this->code_produit = ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_PRODUIT]) ? $datas[ProduitCsvFile::CSV_PRODUIT_CODE_PRODUIT] : null;
        }

        if ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_COMPTABLE_NOEUD] == $this->getTypeNoeud()) {
            $this->code_comptable = ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_COMPTABLE]) ? $datas[ProduitCsvFile::CSV_PRODUIT_CODE_COMPTABLE] : null;
        }

        if ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_DOUANE_NOEUD] == $this->getTypeNoeud()) {
            $this->code_douane = ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_DOUANE]) ? $datas[ProduitCsvFile::CSV_PRODUIT_CODE_DOUANE] : null;
        }
    }

    public abstract function getTypeNoeud();

    public function getDetailConfiguration() {
        try {
            $parent_node = $this->getParentNode();
        } catch (Exception $e) {
            return $this->getDetail();
            ;
        }

        $details = $this->getParentNode()->getDetailConfiguration();
        if ($this->exist('detail')) {
            foreach ($this->detail as $type => $detail) {
                foreach ($detail as $noeud => $droits) {
                    if ($droits->readable !== null)
                        $details->get($type)->get($noeud)->readable = $droits->readable;
                    if ($droits->writable !== null)
                        $details->get($type)->get($noeud)->writable = $droits->writable;
                }
            }
        }
        return $details;
    }

    public function getKeys($noeud) {
        if ($noeud == $this->getTypeNoeud()) {

            return array($this->getKey() => $this);
        }

        $items = array();
        foreach ($this->getChildrenNode() as $key => $item) {
            $items = array_merge($items, $item->getKeys($noeud));
        }

        return $items;
    }

    public function addInterpro($interpro) {
        if ($this->exist('interpro')) {
            $this->interpro->getOrAdd($interpro);
        }
        return $this->getParentNode()->addInterpro($interpro);
    }

    public function hasDepartements() {
        return false;
    }

    public function hasDroits() {
        return true;
    }

    public function hasLabels() {
        return false;
    }

    public function hasDetails() {
        return false;
    }

    public function hasDroit($type) {
        if (!$this->hasDroits()) {

            return false;
        }

        if ($type == ConfigurationDroits::DROIT_CVO) {
            return true;
        }

        return false;
    }

    public function hasCodes() {
        return false;
    }

}
