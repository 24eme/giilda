<?php

class DRMCalendrier {

    protected $etablissements = null;
    protected $etablissement = null;
    protected $campagne = null;
    protected $periodes = null;
    protected $drms = null;
    protected $isTeledeclarationMode = false;
    protected $multiEtbs;

    const VIEW_INDEX_ETABLISSEMENT = 0;
    const VIEW_CAMPAGNE = 1;
    const VIEW_PERIODE = 2;
    const VIEW_VERSION = 3;
    const VIEW_MODE_SAISIE = 4;
    const VIEW_STATUT = 5;
    const VIEW_STATUT_DOUANE_ENVOI = 6;
    const VIEW_STATUT_DOUANE_ACCUSE = 7;
    const VIEW_NUMERO_ARCHIVAGE = 8;
    const STATUT_NOUVELLE = 'NOUVELLE';
    const STATUT_EN_COURS = 'EN_COURS';
    const STATUT_VALIDEE = 'VALIDEE';
    const STATUT_CLOTURE = 'EN_COURS';

    public function __construct($etablissement, $campagne, $isTeledeclarationMode = false) {
        $this->etablissement = $etablissement;
        $this->campagne = $campagne;
        $this->isTeledeclarationMode = $isTeledeclarationMode;
        $this->periodes = $this->buildPeriodes();


        $this->etablissements = $this->etablissement->getSociete()->getEtablissementsObj(false);

        $this->multiEtbs = ((count($this->etablissement) > 1) && $this->isTeledeclarationMode);

        $this->loadDRMs();
    }

    protected function buildPeriodes() {

        if ($this->campagne == -1) {
            if ($this->isTeledeclarationMode) {
                return DRMClient::getInstance()->getLastMonthPeriodes(6);
            } else {
                return DRMClient::getInstance()->getLastMonthPeriodes(12);
            }
        }
        return DRMClient::getInstance()->getPeriodes($this->campagne);
    }

    protected function loadDRMs() {
        $this->drms = array();


        if ($this->multiEtbs) {
            foreach ($this->etablissements as $etablissement) {
                $etbIdentifiant = $etablissement->etablissement->identifiant;
                if (!array_key_exists($etbIdentifiant, $this->drms)) {
                    $this->drms[$etbIdentifiant] = array();
                }
                $drms = DRMClient::getInstance()->viewByIdentifiantAndCampagne($etbIdentifiant, $this->campagne);
                foreach ($drms as $drm) {
                    if (array_key_exists($drm[self::VIEW_PERIODE], $this->drms[$etbIdentifiant])) {

                        continue;
                    }
                    $this->drms[$etbIdentifiant][$drm[self::VIEW_PERIODE]] = $drm;
                }
            }
        } else {
            $drms = DRMClient::getInstance()->viewByIdentifiantAndCampagne($this->etablissement->identifiant, $this->campagne);

            foreach ($drms as $drm) {
                if (array_key_exists($drm[self::VIEW_PERIODE], $this->drms)) {

                    continue;
                }

                $this->drms[$drm[self::VIEW_PERIODE]] = $drm;
            }
        }
    }

    public function getIdentifiant() {

        return $this->etablissement->identifiant;
    }

    public function getEtablissement() {

        return $this->etablissement;
    }

    public function getPeriodeVersion($periode, $etablissement = false) {
        if (!$this->hasDRM($periode, $etablissement)) {

            return;
        }

        if ($etablissement && $this->multiEtbs) {
            $drm = $this->drms[$etablissement->identifiant][$periode];
        } else {
            $drm = $this->drms[$periode];
        }

        return DRMClient::getInstance()->buildPeriodeAndVersion($drm[self::VIEW_PERIODE], $drm[self::VIEW_VERSION]);
    }

    public function getPeriodes() {

        return $this->periodes;
    }

    public function hasDRM($periode, $etablissement = false) {
        if ($etablissement && $this->multiEtbs) {
            return isset($this->drms[$etablissement->identifiant][$periode]);
        }
        return isset($this->drms[$periode]);
    }

    public function getId($periode) {
        if (!$this->hasDRM($periode)) {

            return;
        }

        $drm = $this->drms[$periode];

        return DRMClient::getInstance()->buildId($drm[self::VIEW_INDEX_ETABLISSEMENT], $drm[self::VIEW_PERIODE], $drm[self::VIEW_VERSION]);
    }

    public function getPeriodeLibelle($periode) {
        return ConfigurationClient::getInstance()->getPeriodeLibelle($periode);
    }

    public function getMoisLibelle($periode) {
        return ConfigurationClient::getInstance()->getMoisLibelle($periode);
    }

    public function getNumero($periode) {

        return $this->getPeriodeVersion($periode);
    }

    public function getStatut($periode, $etablissement = false) {

        if (!$this->hasDRM($periode, $etablissement)) {

            return self::STATUT_NOUVELLE;
        }

        if ($etablissement && $this->multiEtbs) {
            $drm = $this->drms[$etablissement->identifiant][$periode];
        } else {
            $drm = $this->drms[$periode];
        }

        if ($drm[self::VIEW_STATUT]) {

            return self::STATUT_VALIDEE;
        }

        return self::STATUT_EN_COURS;
    }

    public function getNumeroArchivage($periode) {
        if (!$this->hasDRM($periode)) {
            return;
        }
        $drm = $this->drms[$periode];
        return $drm[self::VIEW_NUMERO_ARCHIVAGE];
    }

    public function getStatutLibelle($periode) {
        
    }

    public function getDRM($periode) {
        $id = $this->getId($periode);

        if (!$id) {

            return null;
        }

        return DRMClient::getInstance()->find($id);
    }

    public function getDrmToCompleteAndToStart() {
        $drmToCompleteAndToStart = array();
        foreach ($this->etablissements as $etb) {
            if (!array_key_exists($etb->etablissement->identifiant, $drmToCompleteAndToStart)) {
                $drmToCompleteAndToStart[$etb->etablissement->identifiant] = new stdClass();
                
                $drmToCompleteAndToStart[$etb->etablissement->identifiant]->nom = $etb->etablissement->nom;
                $drmToCompleteAndToStart[$etb->etablissement->identifiant]->nb_drm_to_create = 0;
                $drmToCompleteAndToStart[$etb->etablissement->identifiant]->nb_drm_to_finish = 0;
                $drmToCompleteAndToStart[$etb->etablissement->identifiant]->statuts = array();
            }
            foreach ($this->getPeriodes() as $periode) {
               
                $statut = $this->getStatut($periode, $etb->etablissement);
                if ($statut == self::STATUT_NOUVELLE) {
                    $drmToCompleteAndToStart[$etb->etablissement->identifiant]->nb_drm_to_create++;
                    $drmToCompleteAndToStart[$etb->etablissement->identifiant]->statuts[self::STATUT_NOUVELLE] = self::STATUT_NOUVELLE;
                }
                if ($statut == self::STATUT_EN_COURS) {
                    $drmToCompleteAndToStart[$etb->etablissement->identifiant]->nb_drm_to_finish++;
                    $drmToCompleteAndToStart[$etb->etablissement->identifiant]->statuts[self::STATUT_EN_COURS] = self::STATUT_EN_COURS;
                }
            }
            if ($drmToCompleteAndToStart[$etb->etablissement->identifiant]->nb_drm_to_create + $drmToCompleteAndToStart[$etb->etablissement->identifiant]->nb_drm_to_finish == 0) {
                unset($drmToCompleteAndToStart[$etb->etablissement->identifiant]);
            }
        }
        return $drmToCompleteAndToStart;
    }

}
