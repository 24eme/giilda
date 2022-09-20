<?php

/**
 * Model for MouvementsFacture
 *
 */
class MouvementsFacture extends BaseMouvementsFacture {

    public function constructIds($date) {
        if (!$date) {
            $date = date('Ymd');
        }

        $this->identifiant = MouvementsFactureClient::getInstance()->getNextNoMouvementsFacture($date);
        $this->periode = substr($date, 0, 6);
        $this->_id = MouvementsFactureClient::getInstance()->getId($this->identifiant);
    }

    public function getMvtsByRegion($region = null) {
        $mvts = [];
        foreach ($this->mouvements as $etbKey => $mvtsEtb) {
            foreach ($mvtsEtb as $mvtKey => $mvt) {
                if($region && $mvt->region && $mvt->region != $region) continue;
                if (!isset($mvts[$etbKey])) {
                    $mvts[$etbKey] = [];
                }
                $mvts[$etbKey][$mvtKey] = $mvt;
            }
        }
        return $mvts;
    }

    public function getNbMvts($region = null) {
        $nb_mvt = 0;
        foreach ($this->getMvtsByRegion($region) as $etbKey => $mvtsEtb) {
            $nb_mvt += count($mvtsEtb);
        }
        return $nb_mvt;
    }

    public function getNbMvtsAFacture($region = null) {
        $nb_mvt = 0;
        foreach ($this->getMvtsByRegion($region) as $etbKey => $mvtsEtb) {
            foreach ($mvtsEtb as $mvtKey => $mvt) {
            if ($mvt->facturable && !$mvt->facture) {
                    $nb_mvt ++;
                }
            }
        }
        return $nb_mvt;
    }

    public function getNbSocietes($region = null) {
        return count($this->getMvtsByRegion($region));
    }

    public function getTotalHt($region = null) {
        $montant = 0;
        foreach ($this->getMvtsByRegion($region) as $etbKey => $mvtsEtb) {
            foreach ($mvtsEtb as $mvtKey => $mvt) {
                if ($mvt->facturable) {
                    $montant += $mvt->getPrixHt();
                }
            }
        }
        return $montant;
    }

    public function getTotalHtAFacture($region = null) {
        $montant = 0;
        foreach ($this->getMvtsByRegion($region) as $etbKey => $mvtsEtb) {
            foreach ($mvtsEtb as $mvtKey => $mvt) {
                if ($mvt->facturable && !$mvt->facture) {
                    $montant += $mvt->getPrixHt();
                }
            }
        }
        return $montant;
    }

    public function getLibelleFromId() {
        return "Facturation libre : " . $this->getLibelle() . " (" . Date::francizeDate($this->getDate()) . ")";
    }

    public function findMouvement($cle_mouvement, $part_id = null){
      $cle_mouvement = rtrim($cle_mouvement);
      foreach($this->document->getMouvements() as $identifiant => $mouvements) {
	       if ((!$part_id || preg_match('/^'.$part_id.'/', $identifiant)) && array_key_exists($cle_mouvement, $mouvements->toArray())) {
            return $mouvements[$cle_mouvement];
          }
        }
        throw new sfException(sprintf('The mouvement %s of the document %s does not exist', $cle_mouvement, $this->document->get('_id')));
    }

    public function getStartIndexForSaisieForm($region = null) {
        $index = 0;
        foreach($this->getSortedMvts($region) as $mvt) {
          if ($mvt->facture && $index < $mvt->vrac_numero) {
            $index = $mvt->vrac_numero;
          }
        }
        $index++;
        return $index;
    }

    public function getLastMouvement($region = null) {
        $mvts = $this->getSortedMvts($region);
        if (isset($mvts['999_nouveau_nouveau'])) {
          unset($mvts['999_nouveau_nouveau']);
        }
        return end($mvts);
    }

    public function getSortedMvts($region = null) {
      $result = array();
      foreach($this->getMvtsByRegion($region) as $id => $mvts) {
        foreach($mvts as $key => $mvt) {
          $result[$mvt->getIndexForSaisieForm()] = $mvt;
        }
      }
      ksort($result);
      return $result;
    }

    public function getRegionsFacturables() {
        $regions = [];
        foreach ($this->mouvements as $etbKey => $mvtsEtb) {
            foreach ($mvtsEtb as $mvtKey => $mvt) {
                if ($mvt->region && !in_array($mvt->region, $regions)) {
                    $regions[] = $mvt->region;
                }
            }
        }
        return $regions;
    }

}
