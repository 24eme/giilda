<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class FactureLigne
 * @author mathurin
 */
class FactureLigne extends BaseFactureLigne {

    public function getMouvements() {
        $mouvements = array();
        foreach ($this->origine_mouvements as $idDoc => $mouvsKeys) {
            foreach ($mouvsKeys as $mouvKey) {
                $identifiant = $this->getDocument()->identifiant;
                if(!EtablissementClient::getInstance()->getFormatIdentifiant()) {
                        $identifiant = explode("-", $idDoc)[1];
                }
                $mouvements[] = Factureclient::getInstance()->getDocumentOrigine($idDoc)->findMouvement($mouvKey, $identifiant);
            }
        }
        return $mouvements;
    }

    public function getQuantite() {
        $quantite = 0;
        foreach ($this->details as $detail) {
            $quantite += $detail->quantite;
        }
        return $quantite;
    }

    public function getPrixUnitaire() {
        $prixUnitaire = null;
        foreach ($this->details as $detail) {
            if($prixUnitaire !== null && $prixUnitaire != $detail->prix_unitaire) {
                return null;
            }
            $prixUnitaire = $detail->prix_unitaire;
        }

        return $prixUnitaire;
    }

    public function facturerMouvements() {
        foreach ($this->getMouvements() as $mouv) {
            $mouv->facturer();
        }
    }

    public function setProduitHash($ph) {
        $ret = $this->_set('produit_hash', $ph);

        $this->_set('produit_identifiant_analytique', null);
        $this->getProduitIdentifiantAnalytique();
        return $ret;
    }

    public function getTauxTva() {
        foreach ($this->details as $detail) {
            if ($detail->taux_tva) {

                return $detail->taux_tva;
            }
        }

        return 0;
    }

    public function updateTotaux() {
        $this->montant_ht = 0;
        $this->montant_tva = 0;
        foreach ($this->details as $detail) {
            $detail->montant_ht = $detail->quantite * $detail->prix_unitaire;
            $detail->montant_tva = $detail->taux_tva * $detail->montant_ht;
            if(FactureConfiguration::getInstance()->isPdfLigneDetails()) {
                $detail->montant_ht = round($detail->montant_ht, 2);
                $detail->montant_tva = round($detail->montant_tva, 2);
            }

            $this->montant_ht += $detail->montant_ht;
            $this->montant_tva += $detail->montant_tva;
        }

        $this->montant_ht = round($this->montant_ht, 2);
        $this->montant_tva = round($this->montant_tva, 2);
    }

    /* public function getProduitIdentifiantAnalytique() {
      $id = $this->_get('produit_identifiant_analytique');
      if ($id) {
      return $id;
      }
      $code = $this->getConfProduit()->getCodeComptable();
      $this->_set('produit_identifiant_analytique', $code);
      return $code;
      } */

    public function getOrigineIdentifiant() {
        foreach ($this->origine_mouvements as $docId => $origines) {

            return $docId;
        }

        return null;
    }

    public function getOrigineType() {
        foreach ($this->origine_mouvements as $origines) {
            foreach ($origines as $templateId) {

                return $templateId;
            }
        }

        return null;
    }

    public function getConfProduit() {
        return ConfigurationClient::getCurrent()->get($this->produit_hash);
    }

    public function defacturerMouvements() {
        foreach ($this->getMouvements() as $mouv) {
            $mouv->defacturer();
        }
    }

    public function cleanDetails() {
        $detailsToRemove = array();
        foreach ($this->details as $detail) {
            if (!$detail->prix_unitaire && !$detail->libelle && !$detail->quantite) {
                $detailsToRemove[$detail->getKey()] = true;
            }
        }

        foreach ($detailsToRemove as $key => $void) {
            $this->details->remove($key);
        }
    }

    public function setLibelle($l) {
        $this->_set('libelle', str_replace('"','', $l));
    }

    public function getLibelle() {
       return str_replace('"','', $this->_get('libelle'));
    }

    public function getLibellePrincipal() {

        return trim(preg_replace("/\(.*\)/", "", $this->libelle));
    }

    public function getLibelleSecondaire() {
        if (!preg_match("/\(.*\)/", $this->libelle)) {

            return null;
        }
        return trim(preg_replace("/.*(\(.*\)).*/", '\1', $this->libelle));
    }

}
