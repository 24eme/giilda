<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAEExportCsvEdi
 *
 * @author mathurin
 */
class DAEExportCsvEdi extends DAECsvEdi {

    protected $etablissement =null;

    public function __construct($daes = array()) {
        parent::__construct(null, $daes);
    }

    public function exportEDI() {
        if (!count($this->daes)) {
            throw new sfException('Absence de DAES');
        }
        $header = $this->createHeaderEdi();
        $body = $this->createBodyEdi();
        return $header . $body;
    }

    private function createHeaderEdi() {
        $entete = "#date de la commercialisation;identifiant declarvins du déclarant;numéro d'accises du déclarant;nom du déclarant;stat famille;stat sous famille;stat département;";
        $entete .="code ou nom de la certification du vin;nom ou code du genre du vin;nom ou code du appellation du vin;nom ou code du mention du vin;nom ou code du lieu du vin;nom ou code du couleur du vin;nom ou code du cépage du vin;Le complément du vin;Le libellé personnalisé du vin;label du produit;mention de domaine ou château revendiqué;millésime;";
        $entete .="n° accise de l'acheteur;nom acheteur;type acheteur;nom du pays de destination;type de conditionnement;libellé conditionnement;contenance conditionnement en litres;quantité de conditionnement;prix unitaire;stat qtt hl;stat prix hl\n";
        return $entete;
    }

    private function createBodyEdi() {
        $body = "";
        foreach ($this->daes as $d_row) {
            $dae = DAEClient::getInstance()->find($d_row->_id);
            $this->getEtablissement($dae);
            $body.= $this->createDeclarantEdi($dae);
            $body.= $this->createProduitEdi($dae);
            $body.= $this->createCommercialisationEdi($dae);
            $body.="\n";
        }
        return $body;
    }

    private function getEtablissement($dae){
        if(!$this->etablissement || ($this->etablissement->identifiant != $dae->identifiant)){
            $this->etablissement = EtablissementClient::getInstance()->findByIdentifiant($dae->identifiant);
        }
        return $this->etablissement;
    }

    private function createDeclarantEdi($dae) {
        $etb = $this->getEtablissement($dae);
        $cp = ($etb->siege->code_postal)? preg_replace("/([0-9]{2})[0-9]{3}/","$1",$etb->siege->code_postal) : "";
        return $dae->date.";".$dae->identifiant.";".CurrentClient::getCurrent()->anonymisation($this->etablissement->no_accises).";".CurrentClient::getCurrent()->anonymisation($dae->declarant->nom).";".$etb->famille.";".$etb->sous_famille.";".$cp.";";
    }

    private function createProduitEdi($dae) {
        $produit = $dae->getProduitObject();
        $certification = $produit->getCertification()->getLibelle();
        $genre = $produit->getGenre()->getLibelle();
        $appellation = $produit->getAppellation()->getLibelle();
        $mention = $produit->getMention()->getLibelle();
        $lieu = $produit->getLieu()->getLibelle();
        $couleur = $produit->getLibelle();

        $complement = "";
        return $certification .
          ";" .$genre.
          ";" .$appellation.
          ";" .$mention.
          ";" .$lieu.
          ";" .$couleur.
          ";".
          ";". $complement.
          ";". $dae->produit_libelle.
          ";". $dae->label_libelle.
          ";". $dae->mention_libelle.
          ";". $dae->millesime.";";
    }

    private function createCommercialisationEdi($dae){
        return CurrentClient::getCurrent()->anonymisation($dae->no_accises_acheteur).";"
        .CurrentClient::getCurrent()->anonymisation($dae->nom_acheteur).";"
        .$dae->type_acheteur_libelle.";"
        .$dae->destination_libelle.";"
        .$dae->conditionnement_libelle.";"
        .$dae->contenance_libelle.";"
        .($dae->contenance_hl*100).";"
        .$dae->quantite.";"
        .$dae->prix_unitaire.";"
        .$dae->volume_hl.";"
        .$dae->prix_hl;
    }

}
