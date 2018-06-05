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
                $mouvements[] = Factureclient::getInstance()->getDocumentOrigine($idDoc)->findMouvement($mouvKey, $this->getDocument()->identifiant);
            }
        }
        return $mouvements;
    }

    public function facturerMouvements() {
        foreach ($this->getMouvements() as $mouv) {
            $mouv->facturer();
        }
    }

    public function setProduitHash($ph) {
        $ret = $this->_set('produit_hash', $ph);
        //Remove identifiant_analytique from cache and set the new one
        $this->_set('produit_identifiant_analytique', null);
        $this->getProduitIdentifiantAnalytique();
        return $ret;
    }

    public function getProduitIdentifiantAnalytique() {
        $id = $this->_get('produit_identifiant_analytique');
        if ($id) {
            return $id;
        }
        $code = $this->getConfProduit()->getCodeComptable();
        $this->_set('produit_identifiant_analytique', $code);
        return $code;
    }

    public function getDate() {
        $date = preg_replace("/^([0-9]{4})([0-9]{2})$/", '\1-\2-01', $this->origine_date);

        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}/", $date)) {
            $date = preg_replace("/^([0-9]{4})-([0-9]{4})$/", '\1-08-01', $this->origine_date);
            if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}/", $date)) {
                throw new sfException(sprintf("La date d'origine du document n'est pas au bon format %s", $date));
            }
        }

        return $date;
    }

    public function getConf() {

        return ConfigurationClient::getConfiguration($this->getDate());
    }

    public function getConfProduit() {
        $produitHash = $this->produit_hash;
        $m = array();
        if(preg_match('/(.*)\/details[a-zA-Z0-9]+\/[a-zA-Z0-9]+$/',$produitHash,$m)){
            $produitHash = $m[1];
        }
        return $this->getConf()->get($produitHash);
    }

    public function defacturerMouvements() {
        foreach ($this->getMouvements() as $mouv) {
            $mouv->defacturer();
        }
    }

}
