<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {

    public function getConfig() {
        return $this->getParent()->getConfigDetails();
    }

    public function getLibelle($format = "%format_libelle%", $label_separator = ", ") {

        return $this->getCepage()->getConfig()->getLibelleFormat($this->labels->toArray(), $format, $label_separator);
    }

    public function getCode($format = "%g%%a%%m%%l%%co%%ce%") {

        return $this->getCepage()->getConfig()->getCodeFormat($format);
    }

    public function getCodeProduit() {

        return $this->getCepage()->getConfig()->getCodeProduit();
    }

    /**
     *
     * @return DRMCepage
     */
    public function getCepage() {

        return $this->getParent()->getParent();
    }

    /**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {

        return $this->getCepage()->getCouleur();
    }

    /**
     *
     * @return DRMLieu
     */
    public function getLieu() {

        return $this->getCouleur()->getLieu();
    }

    /**
     *
     * @return DRMMention
     */
    public function getMention() {

        return $this->getLieu()->getMention();
    }

    /**
     *
     * @return DRMAppellation
     */
    public function getAppellation() {

        return $this->getLieu()->getAppellation();
    }

    public function hasProduitDetailsWithStockNegatif() {

        return $this->getCepage()->hasProduitDetailsWithStockNegatif();
    }

    public function getGenre() {
        return $this->getAppellation()->getGenre();
    }

    public function getCertification() {
        return $this->getGenre()->getCertification();
    }

    public function getLabelKeyString() {
        if ($this->labels) {
            return implode('|', $this->labels->toArray());
        }

        return '';
    }

    public function getLabelKey() {
        $key = null;
        if ($this->labels) {
            $key = implode('-', $this->labels->toArray());
        }
        return ($key) ? $key : DRM::DEFAULT_KEY;
    }

    public function getLabelsLibelle($format = "%la%", $label_separator = ", ") {

        return $this->getConfig()->getDocument()->formatLabelsLibelle($this->labels->toArray(), $format, $label_separator);
    }

    public function canSetStockDebutMois() {
        return !$this->hasPrecedente();
    }

    public function canSetStockInitial() {
        //TODO : Parametrer en fct de la DATE DRM
        return true;
    }

    public function canSetLabels() {

        return !$this->hasPrecedente();
    }

    public function hasPrecedente() {
        if (!$this->getDocument()->hasPrecedente()) {

            return false;
        }


        return $this->getDocument()->getPrecedente()->exist($this->getHash());
    }

    protected function update($params = array()) {
        parent::update($params);

        $this->total_debut_mois = $this->stocks_debut->initial;

        if ($this->sorties->exist('vrac_details')) {
            $this->sorties->vrac = 0;
            foreach ($this->sorties->vrac_details as $vrac_detail) {
                $this->sorties->vrac+=$vrac_detail->volume;
            }
        }
        if ($this->sorties->exist('export_details')) {
            $this->sorties->export = 0;
            foreach ($this->sorties->export_details as $export_detail) {
                $this->sorties->export+=$export_detail->volume;
            }
        }
        if ($this->sorties->exist('cooperative_details')) {
            $this->sorties->cooperative = 0;
            foreach ($this->sorties->cooperative_details as $cooperative_detail) {
                $this->sorties->cooperative+=$cooperative_detail->volume;
            }
        }
        $this->total_entrees = $this->getTotalByKey('entrees', 'recolte');
        $this->total_sorties = $this->getTotalByKey('sorties', 'recolte');

        $this->stocks_fin->final = $this->stocks_debut->initial + $this->total_entrees - $this->total_sorties;

        $this->total_entrees_revendique = $this->getTotalByKey('entrees', 'revendique');
        $this->total_sorties_revendique = $this->getTotalByKey('sorties', 'revendique');
        $this->stocks_fin->dont_revendique = $this->stocks_debut->dont_revendique + $this->total_entrees_revendique - $this->total_sorties_revendique;
        if ($this->entrees->exist('recolte')) {
            $this->total_recolte = $this->entrees->recolte;
        }

        $this->total_facturable = 0;
        $this->updateNoeud('entrees', -1);
        $this->updateNoeud('sorties', 1);

        $this->cvo->volume_taxable = $this->total_facturable;

        $this->total = $this->stocks_fin->final;
        $this->total_revendique = $this->stocks_fin->dont_revendique;
    }

    protected function updateNoeud($hash, $coefficient_facturable) {
        foreach ($this->get($hash) as $key => $volume) {
            if (!$this->getConfig()->exist($hash . "/" . $key)) {
                continue;
            }
            $config = $this->getConfig()->get($hash . "/" . $key);

            if ($config->facturable) {
                $this->total_facturable += $volume * $coefficient_facturable;
            }
        }
    }

    private function getTotalByKey($key, $onlyCaracteristique = false) {
        $sum = 0;
        foreach ($this->get($key, true) as $n => $k) {
            if (!is_object($k)) {
                if ($onlyCaracteristique) {
                    if ($k && $this->getConfig()->$key->$n->$onlyCaracteristique) {
                        $sum += $k;
                    }
                } else {
                    $sum += $k;
                }
            }
        }
        return $sum;
    }

    public function getTotalDebutMois() {
        if (is_null($this->_get('total_debut_mois'))) {
            return 0;
        } else {
            return $this->_get('total_debut_mois');
        }
    }

    public function nbToComplete() {
        return $this->hasMouvementCheck();
    }

    public function nbComplete() {
        return $this->isComplete();
    }

    public function isComplete() {
        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }

    public function getIdentifiantHTML() {
        return strtolower(str_replace($this->getDocument()->declaration->getHash(), '', str_replace('/', '_', preg_replace('|\/[^\/]+\/DEFAUT|', '', $this->getHash()))));
    }

    public function hasContratVrac() {
        $rows = $this->getContratsVrac();
        return count($rows);
    }

    public function getContratsVrac() {
        $contrats = $this->getContratsVracByHash($this->getCepage()->getHash());

        $correspondance_hash = $this->getCepage()->getConfig()->getCorrespondanceHash();
        if ($correspondance_hash) {
            $contrats = array_merge($contrats, $this->getContratsVracByHash($correspondance_hash));
        }

        return $contrats;
    }

    public function getContratsVracByHash($hash) {
        return DRMClient::getInstance()->getContratsFromProduit($this->getDocument()->identifiant, $hash, array(VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE, VracClient::TYPE_TRANSACTION_VIN_VRAC));
    }

    public function isModifiedMother($key) {

        return $this->getDocument()->isModifiedMother($this->getHash(), $key);
    }

    public function getDroitVolume($type) {

        return $this->sommeLignes(DRMDroits::getDroitSorties()) - $this->sommeLignes(DRMDroits::getDroitEntrees());
    }

    protected function init($params = array()) {
        parent::init($params);

        $keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;

        $this->stocks_debut->initial = ($keepStock) ? $this->total : null;
        $this->stocks_debut->revendique = $this->total_revendique;
        $this->total_entrees = null;
        $this->total_sorties = null;
        $this->total_revendique = null;
        $this->total = null;
    }

    public function sommeLignes($lines) {
        $sum = 0;
        foreach ($lines as $line) {
            $sum += $this->get($line);
        }
        return $sum;
    }

    public function hasStockFinDeMoisDRMPrecedente() {
        $result = false;
        $drmPrecedente = $this->getDocument()->getPrecedente();
        if (!$drmPrecedente->isNew()) {
            if ($drmPrecedente->exist($this->getHash())) {
                if ($drmPrecedente->get($this->getHash())->total) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function hasMouvement() {

        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }

    public function hasStockEpuise() {

        return $this->total_debut_mois == 0 && !$this->hasMouvement() && $this->total == 0;
    }

    public function isSupprimable() {

        return $this->hasStockEpuise() && !$this->getDocument()->hasVersion();
    }

    public function hasMouvementCheck() {

        return !$this->pas_de_mouvement_check;
    }

    public function getMouvements() {

        return array_replace_recursive($this->getMouvementsByNoeud('entrees'), $this->getMouvementsByNoeud('sorties'));
    }

    public function getMouvementsByNoeud($hash) {
        $mouvements = array();
        foreach ($this->get($hash) as $key => $volume) {
            if ($volume instanceof acCouchdbJson) {

                continue;
            }
            if (!$this->getConfig()->exist($hash . "/" . $key)) {
                continue;
            }
            $mouvement = DRMMouvement::freeInstance($this->getDocument());
            $mouvement->produit_hash = $this->getCepage()->getConfig()->getHash();
            $mouvement->facture = 0;
            $mouvement->region = $this->getDocument()->region;
            $mouvement->cvo = $this->getCVOTaux();
            $mouvement->facturable = ($this->getConfig()->get($hash . "/" . $key)->facturable && $mouvement->cvo > 0) ? 1 : 0;
            $mouvement->version = $this->getDocument()->getVersion();
            $mouvement->date_version = ($this->getDocument()->valide->date_saisie) ? ($this->getDocument()->valide->date_saisie) : date('Y-m-d');
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE;

            if ($this->exist($hash . "/" . $key . "_details")) {
                $mouvements = array_replace_recursive($mouvements, $this->get($hash . "/" . $key . "_details")->createMouvements($mouvement));
                continue;
            }

            $mouvement = $this->createMouvement(clone $mouvement, $hash . '/' . $key, $volume);
            if (!$mouvement) {
                continue;
            }

            $mouvements[$this->getDocument()->getIdentifiant()][$mouvement->getMD5Key()] = $mouvement;
        }

        return $mouvements;
    }

    public function createMouvement($mouvement, $hash, $volume) {
        if ($this->getDocument()->hasVersion() && !$this->getDocument()->isModifiedMother($this, $hash)) {
            return null;
        }

        if ($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($this->getHash() . '/' . $hash)) {
            $volume = $volume - $this->getDocument()->motherGet($this->getHash() . '/' . $hash);
        }

        $config = $this->getConfig()->get($hash);
        $volume = $config->mouvement_coefficient * $volume;

        if ($volume == 0) {
            return null;
        }

        $mouvement->type_hash = $hash;
        $mouvement->type_libelle = $config->getLibelle();
        $mouvement->volume = $volume;
        $mouvement->date = $this->getDocument()->getDate();

        return $mouvement;
    }

    public function getCVOTaux() {
        $this->cvo->calcul();

        return $this->cvo->taux;
    }

    public function storeDroits() {
        $this->cvo->taux = null;
        $this->cvo->calcul();
    }

    public function isEdited() {
        return $this->getCepage()->exist('edited') && $this->getCepage()->edited;
    }

    public function hasMovements() {
        if ($this->hasMouvement()) {

            return true;
        }

        return $this->getCepage()->hasMovements();
    }

    public function updateDroitsDouanes() {
        $droitsNode = $this->getDocument()->getOrAdd('droits')->getOrAdd('douane');
        $cepageConfig = $this->getCepage()->getConfig();
        $genreKey = $this->getGenre()->getKey();

        foreach ($this->getEntrees() as $entreeKey => $entree) {
            $entreeKey = str_replace('_details', '', $entreeKey);
            if (!$this->getConfig()->exist('entrees/' . $entreeKey)) {
                continue;
            }
            $entreeConf = $this->getConfig()->get('entrees/' . $entreeKey);
            $entreeDrm = $this->get('entrees/' . $entreeKey);

            if ($entreeConf->taxable_douane && $entreeDrm && $entreeDrm > 0) {
                $droitsNode->updateDroitDouane($genreKey, $cepageConfig, $entreeDrm, true);
            }
        }
        foreach ($this->getSorties() as $sortieKey => $sortie) {

            $sortieKey = str_replace('_details', '', $sortieKey);
            if (!$this->getConfig()->exist('sorties/' . $sortieKey)) {
                continue;
            }
            $sortieConf = $this->getConfig()->get('sorties/' . $sortieKey);

            $sortieDrm = $this->get('sorties/' . $sortieKey);


            if ($sortieConf->taxable_douane && $sortieDrm && $sortieDrm > 0) {
                $droitsNode->updateDroitDouane($genreKey, $cepageConfig, $sortieDrm, false);
            }
        }
    }

    public function hasTypeDoc($nodeName) {
        $nodeNameComplete = $nodeName . '_details';
        foreach ($this->sorties->$nodeNameComplete as $detailRow) {

            if ($detailRow->type_document) {
                return true;
            }
        }
        return false;
    }

}
