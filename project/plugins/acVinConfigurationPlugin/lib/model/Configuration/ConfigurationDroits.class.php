<?php

/**
 * Model for ConfigurationDroits
 *
 */
class ConfigurationDroits extends BaseConfigurationDroits {

    const CODE_CVO = 'CVO';
    const CODE_DOUANE = 'DOUANE';
    const LIBELLE_CVO = 'Cvo';
    const DROIT_CVO = 'cvo';
    const DROIT_DOUANE = 'douane';

    protected $currentDroits = array();

    public function addDroit($date, $taux, $code, $libelle) {
        $value = $this->add();
        $value->date = $date;
        $value->taux = $taux;
        $value->code = $code;
        $value->libelle = $libelle;
    }

    public function getCurrentDroit($date_cvo, $sumtree = true) {
        if ($this->currentDroits) {
            if ($sumtree) {
                $taux = $this->currentDroits->getTaux();
                if (is_array($taux)) {
                    $sign = $taux[0];
                    $value = $taux[1];
                    $parentCVO = $this->getNoeud()->getParentNode()->interpro->getOrAdd($this->getInterpro()->getKey())->droits->getOrAdd($this->getKey())->getCurrentDroit($date_cvo);
                    if ($sign == '+') {
                        $this->currentDroits->taux = $parentCVO->taux + $value;
                    } elseif ($sign == '-') {
                        $this->currentDroits->taux = $parentCVO->taux - $value;
                    }
                }
            }
            return $this->currentDroits;
        }

        $currentDroit = null;
        foreach ($this as $configurationDroit) {
            $date = new DateTime($configurationDroit->date);
            if ($date_cvo >= $date->format('Y-m-d')) {
                if ($currentDroit) {
                    if ($date->format('Y-m-d') > $currentDroit->date) {
                        $currentDroit = $configurationDroit;
                    }
                } else {
                    $currentDroit = $configurationDroit;
                }
            }
        }

        if ($currentDroit) {
            $this->currentDroits = $currentDroit;

            return $this->currentDroits;
        }

//        try {
        $parent = $this->getNoeud()->getParentNode();

        $this->currentDroits = $parent->interpro->getOrAdd($this->getInterpro()->getKey())->droits->getOrAdd($this->getKey())->getCurrentDroit($date_cvo);

        return $this->currentDroits;
//        } catch (sfException $e) {
//	    throw new sfException('Aucun droit spécifié pour '.$this->getHash());
//        }
    }

    public function compressDroits() {
        $droits_to_remove = array();
        $moreRecent = null;
        foreach ($this as $droit) {
            if (!$moreRecent || $droit->date > $moreRecent->date) {
                $moreRecent = $droit;
            }
        }

        if ($moreRecent) {
            $this->clear();
            $this->add(null, $droit);
        }
    }

    public function getInterpro() {
        return $this->getParent()->getParent();
    }

    public function getNoeud() {

        return $this->getInterpro()->getParent()->getParent();
    }

}
