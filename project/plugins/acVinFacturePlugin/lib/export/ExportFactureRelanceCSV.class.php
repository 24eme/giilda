<?php

class ExportFactureRelanceCSV {

    protected $facture = null;
    protected $header = false;

    protected $floatHelper = null;

    public function __construct($doc_or_id, $header = true, $que_les_non_verses_comptablement = false) {
        if ($doc_or_id instanceof Facture) {
            $this->facture = $doc_or_id;
        } else {
            $this->facture = FactureClient::getInstance()->find($doc_or_id);
        }

        if (!$this->facture) {
            echo sprintf("WARNING;Le document n'existe pas %s\n", $doc_or_id);
            return;
        }
        $this->header = $header;
    }

    public static function getHeaderCsv() {
        return "Date de relance;Numéro relance;Nieme Relance;Identifiant;Raison Sociale;Adresse;Adresse complementaire;Commune;Code postal;SIRET;Numero d'adhérent;Code comptable;Numéro facture;Date de facture;Montant TTC;Montant Du;Date derniere relance;Facture doc ID;Interpro\n";
    }

    public function export() {
        $csv = null;

        if($this->header) {

            $csv .= $this->getHeaderCsv();
        }

        $societe = $this->facture->getSociete();
        $idRelance = date('Ymd').$this->facture->code_comptable_client;
        $numberRelance = $this->facture->getNumberToRelance();

        $csv .= date('Y-m-d').";".$idRelance.";".$numberRelance.";".$this->facture->identifiant.";".$societe->raison_sociale.";".$this->protectString($societe->siege->adresse).";".$this->protectString($societe->siege->adresse_complementaire).";".$this->protectString($societe->siege->commune).";".$societe->siege->code_postal.";".$societe->siret.";".$this->facture->numero_adherent.";".$this->facture->code_comptable_client.";".$this->facture->numero_piece_comptable.";".$this->facture->date_facturation.";".$this->facture->total_ttc.";".$this->facture->getRestantDu().";".$this->facture->getDateDerniereRelance().";".$this->facture->_id.";".$this->facture->getOrAdd('interpro')."\n";

        return $csv;
    }

    protected function protectString($value) {

        return str_replace(';', '', $value);
    }
}
