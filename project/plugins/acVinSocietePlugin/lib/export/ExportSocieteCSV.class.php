<?php

class ExportSocieteCSV implements InterfaceDeclarationExportCsv {

    protected $societe = null;
    protected $header = false;
    protected $region = null;

    public static function getHeaderCsv() {

        return "Identifiant;Titre;Raison sociale;Adresse;Adresse 2;Adresse 3;Code postal;Commune;Pays;Code comptable;Code NAF;Siret;TVA Intra;Téléphone;Téléphone portable;Fax;Email;Site;Région;Type;Statut;En alerte;Date de modification;Observation;Organisme;Societe ID\n";
    }

    public function __construct($societe, $header = true, $region = null) {
        $this->societe = $societe;
        $this->header = $header;
        $this->region = $region;
    }

    public function getFileName() {
        $name = $this->societe->_id;
        $name .= $this->societe->_rev;
        return  $name . '.csv';
    }

    public function export() {
        $csv = null;
        if($this->header) {
            $csv .= self::getHeaderCsv();
        }

        $data = $this->exportData();
        $data['commentaire'] = '"'.$data['commentaire'].'"';
        $csv .= implode(';', $data);
        $csv .= "\n";

        return $csv;
    }

    public function exportData() {
        $data = array();

        $adresses_complementaires = explode(' − ', str_replace(array('"', ',', ';'),array('','',''), $this->societe->siege->adresse_complementaire));
        $adresse_complementaire = array_shift($adresses_complementaires);
        $extractIntitule = Societe::extractIntitule($this->societe->raison_sociale);
        $intitule = $extractIntitule[0];
        $raisonSociale = $extractIntitule[1];

        $data['identifiant'] = $this->societe->identifiant;
        $data['intitule'] = $intitule;
        $data['raison_sociale'] = $raisonSociale;
        $data['adresse_1'] = str_replace(array('"',',', ';'), array('','', ''), $this->societe->siege->adresse);
        $data['adresse_2'] = str_replace(array('"',',', ';'), array('','', ''), $adresse_complementaire);
        $data['adresse_3'] = implode(' − ', $adresses_complementaires);
        $data['code_postal'] = $this->societe->siege->code_postal;
        $data['commune'] = $this->societe->siege->commune;
        $data['pays'] = $this->societe->siege->pays;
        $data['code_comptable'] = $this->societe->code_comptable_client;
        $data['naf'] = ""; //NAF
        $data['siret'] = $this->societe->siret;
        $data['no_tva_intracommunautaire'] = $this->societe->no_tva_intracommunautaire;
        $data['telephone'] = preg_replace('/[^\+0-9]/i', '', $this->societe->telephone);
        $data['telephone_mobile'] = preg_replace('/[^\+0-9]/i', '', $this->societe->telephone_mobile);
        $data['fax'] = preg_replace('/[^\+0-9]/i', '', $this->societe->fax);
        $data['email'] = str_replace(';', '.', $this->societe->email);
        $data['site_internet'] = str_replace(array(',', ';', "\n", "\r"), array(' / ', ' / ', ' '), $this->societe->site_internet);
        $data['region'] = "";
        $data['type'] = $this->societe->type_societe;
        $data['statut'] = (($this->societe->statut) ? $this->societe->statut : EtablissementClient::STATUT_ACTIF);
        $data['en_alerte'] = ($this->societe->getMasterCompte()) ? $this->societe->getMasterCompte()->isEnAlerte() : '';
        $data['date_modification'] = $this->societe->date_modification;
        $data['commentaire'] = str_replace('"', "''", str_replace(array(',', ';', "\n", "\r"), array(' / ', ' / ', ' '), $this->societe->commentaire));
        $data['organisme'] = Organisme::getCurrentOrganisme();
        $data['societe_id'] = $this->societe->_id;

        return $data;
    }

    public function exportJson() {

        return json_encode($this->exportData(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    public function setExtraArgs($args) {
    }

}
