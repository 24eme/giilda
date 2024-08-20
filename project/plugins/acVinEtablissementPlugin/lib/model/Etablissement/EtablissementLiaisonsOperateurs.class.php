<?php
/**
 * Model for EtablissementLiaisonsOperateurs
 *
 */

class EtablissementLiaisonsOperateurs extends BaseEtablissementLiaisonsOperateurs {

    public function getChai(){
        if(!$this->hash_chai || !$this->id_etablissement) {

            return null;
        }

        $etablissement = $this->getEtablissement();

        if(!$etablissement || !$etablissement->exist($this->hash_chai)){

            return null;
        }

        return $etablissement->get($this->hash_chai);
    }

    public function getEtablissementIdentifiant() {

        return str_replace("ETABLISSEMENT-", "", $this->id_etablissement);
    }

    public function getEtablissement() {

        return EtablissementClient::getInstance()->find($this->id_etablissement);
    }

    public function getTypeLiaisonLibelle() {
        $types_liaisons = EtablissementClient::getTypesLiaisons();

        return $types_liaisons[$this->type_liaison];
    }
}
