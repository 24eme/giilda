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
    const STATUT_EN_COURS_NON_TELEDECLARE = 'STATUT_EN_COURS_NON_TELEDECLARE';
    const STATUT_NOUVELLE = 'NOUVELLE';
    const STATUT_EN_COURS = 'EN_COURS';
    const STATUT_VALIDEE = 'VALIDEE';
    const STATUT_VALIDEE_NON_TELEDECLARE = 'STATUT_VALIDEE_NON_TELEDECLARE';
    const STATUT_CLOTURE = 'EN_COURS';

    public function __construct($etablissement, $campagne, $isTeledeclarationMode = false) {
        $this->etablissement = $etablissement;
        $this->campagne = $campagne;
        $this->isTeledeclarationMode = $isTeledeclarationMode;
        $this->periodes = $this->buildPeriodes();

        $this->etablissements = $this->etablissement->getSociete()->getEtablissementsObj(false);

        $this->multiEtbs = ((count($this->etablissements) > 1) && $this->isTeledeclarationMode);

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
                foreach ($this->periodes as $periode) {
                    $drm = DRMClient::getInstance()->viewMasterByIdentifiantPeriode($etbIdentifiant, $periode);

                    if (array_key_exists($drm[self::VIEW_PERIODE], $this->drms[$etbIdentifiant])) {
                        continue;
                    }
                    $this->drms[$etbIdentifiant][$drm[self::VIEW_PERIODE]] = $drm;
                }
            }
        } else {

            foreach ($this->periodes as $periode) {
                $drm = DRMClient::getInstance()->viewMasterByIdentifiantPeriode($this->etablissement->identifiant, $periode);

                if (!$drm) {
                    continue;
                }
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

    public function getStatut($periode, $etablissement = false, $isTeledeclarationMode = true) {

        if (!$this->hasDRM($periode, $etablissement)) {

            return self::STATUT_NOUVELLE;
        }

        if ($etablissement && $this->multiEtbs) {
            $drm = $this->drms[$etablissement->identifiant][$periode];
        } else {
            $drm = $this->drms[$periode];
        }

        if ($drm[self::VIEW_STATUT]) {
            if (!$isTeledeclarationMode) {
                return self::STATUT_VALIDEE_NON_TELEDECLARE;
            } else {
                $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($drm[self::VIEW_INDEX_ETABLISSEMENT], $drm[self::VIEW_PERIODE]);
                if (!$drm->isTeledeclare()) {
                    return self::STATUT_VALIDEE_NON_TELEDECLARE;
                }
                return self::STATUT_VALIDEE;
            }
            return self::STATUT_VALIDEE;
        }
        if (!$isTeledeclarationMode) {
            return self::STATUT_EN_COURS_NON_TELEDECLARE;
        } else {
            $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($drm[self::VIEW_INDEX_ETABLISSEMENT], $drm[self::VIEW_PERIODE]);
            if (!$drm->isTeledeclare()) {
                return self::STATUT_EN_COURS_NON_TELEDECLARE;
            }
            return self::STATUT_EN_COURS;
        }
        return self::STATUT_EN_COURS;
    }

    public function getStatutForAllEtablissements($periode, $etablissement = null) {
        if ($this->multiEtbs) {
            $statuts = array();
            foreach ($this->etablissements as $etablissement) {
                $drm = null;
                if (array_key_exists($periode, $this->drms[$etablissement->etablissement->identifiant])) {
                    $drm = $this->drms[$etablissement->etablissement->identifiant][$periode];
                    if ($drm && !$drm[self::VIEW_STATUT]) {
                        $drmObj = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etablissement->etablissement->identifiant, $periode);

                        if (!$drmObj->isTeledeclare()) {
                            return self::STATUT_EN_COURS_NON_TELEDECLARE;
                        }
                        return self::STATUT_EN_COURS;
                    }
                }
                $statuts[] = $drm[self::VIEW_STATUT];
            }
            $all_valide = true;
            foreach ($statuts as $statut) {
                if (!$statut) {
                    return self::STATUT_NOUVELLE;
                }
            }
            $drmObj = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->etablissement->identifiant, $periode);
            if (!$drmObj->isTeledeclare()) {
                return self::STATUT_VALIDEE_NON_TELEDECLARE;
            }
            return self::STATUT_VALIDEE;
        } elseif ($this->isTeledeclarationMode) {
            foreach ($this->etablissements as $etablissement) {
                $statut = $this->getStatut($periode, $etablissement->etablissement);
                if ($statut == self::STATUT_VALIDEE) {
                    $drmObj = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etablissement->etablissement->identifiant, $periode);
                    if (!$drmObj->isTeledeclare()) {
                        return self::STATUT_VALIDEE_NON_TELEDECLARE;
                    }
                    return self::STATUT_VALIDEE;
                }

                if ($statut == self::STATUT_EN_COURS) {
                    $drmObj = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etablissement->etablissement->identifiant, $periode);
                    if (!$drmObj->isTeledeclare()) {
                        return self::STATUT_EN_COURS_NON_TELEDECLARE;
                    }
                    return self::STATUT_EN_COURS;
                }
                return $statut;
            }
        }
        return $this->getStatut($periode, $etablissement, false);
    }

    public function isTeledeclare($periode, $etablissement = false) {
        if (!$etablissement) {
            $etablissement = $this->etablissement;
        }
        return DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etablissement->identifiant, $periode)->isTeledeclare();
    }

    public function getNumeroArchive($periode, $etablissement =  false) {
        if (!$etablissement) {
            $etablissement = $this->etablissement;
        }
        if (!$this->hasDRM($periode, $etablissement)) {
            return;     
        }   
        if ($etablissement && $this->multiEtbs)
            $drm = $this->drms[$etablissement->identifiant][$periode];
        else
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

    public function getLastDrmToCompleteAndToStart() {
        $drmLastWithStatut = array();
        foreach ($this->etablissements as $etb) {
            if (!array_key_exists($etb->etablissement->identifiant, $drmLastWithStatut)) {
                $drmLastWithStatut[$etb->etablissement->identifiant] = new stdClass();
                $drmLastWithStatut[$etb->etablissement->identifiant]->nom = $etb->etablissement->nom;
                $drmLastWithStatut[$etb->etablissement->identifiant]->statut = self::STATUT_VALIDEE;
                $drmLastWithStatut[$etb->etablissement->identifiant]->periode = null;
            }
            foreach (array_reverse($this->getPeriodes()) as $periode) {
                $statut = $this->getStatut($periode, $etb->etablissement);
                $drmLastWithStatut[$etb->etablissement->identifiant]->periode = $periode;
                if ($statut == self::STATUT_EN_COURS) {
                    $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etb->etablissement->identifiant, $periode);
                    $drmLastWithStatut[$etb->etablissement->identifiant]->drm = $drm;
                    if ($drm->isTeledeclare()) {
                        $drmLastWithStatut[$etb->etablissement->identifiant]->statut = self::STATUT_EN_COURS;
                    } else {
                        $drmLastWithStatut[$etb->etablissement->identifiant]->statut = self::STATUT_EN_COURS_NON_TELEDECLARE;
                    }
                    break;
                }
                if ($statut == self::STATUT_NOUVELLE) {
                    $drmLastWithStatut[$etb->etablissement->identifiant]->statut = self::STATUT_NOUVELLE;
                    break;
                }
                $drmLastWithStatut[$etb->etablissement->identifiant]->drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etb->etablissement->identifiant, $periode);
            }
        }
        return $drmLastWithStatut;
    }

    public function getDrmsToCreateArray() {
        $drmsToCreate = array();
        foreach ($this->getPeriodes() as $periode) {
            if ($this->multiEtbs) {
                foreach ($this->etablissements as $etb) {
                    if ($this->getStatut($periode, $etb->etablissement, true) == self::STATUT_NOUVELLE) {
                        if (!array_key_exists($etb->etablissement->identifiant, $drmsToCreate)) {
                            $drmsToCreate[$etb->etablissement->identifiant] = array();
                        }
                        $drmsToCreate[$etb->etablissement->identifiant][$periode] = true;
                    }
                }
            } else {
                if ($this->getStatut($periode, $this->etablissement, true) == self::STATUT_NOUVELLE) {
                    if (!array_key_exists($this->etablissement->identifiant, $drmsToCreate)) {
                        $drmsToCreate[$this->etablissement->identifiant] = array();
                    }
                    $drmsToCreate[$this->etablissement->identifiant][$periode] = true;
                }
            }
        }
        return $drmsToCreate;
    }

}
