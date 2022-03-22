<?php

class ExportFacturePaiementsCSV {

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
       $this->floatHelper = FloatHelper::getInstance();

        $this->header = $header;
        $this->que_les_non_verses_comptablement = $que_les_non_verses_comptablement;
    }

    public static function getHeaderCsv() {
        return "Identifiant;Raison Sociale;Code comptable client;Numéro facture;Date de paiement;Montant;Type de reglement;Commentaire;Montant restant a payer;Execute;Exporte;Facture doc ID;Paiement ID;Code journal;Numéro remise; Numéro compte\n";
    }

    public function export() {
        if($this->header) {

            $csv .= $this->getHeaderCsv();
        }

        $csv .= $this->exportFacturePaiements();

        return $csv;
    }


    public function exportFacturePaiements($date_max = null, $set_verse = false) {

        $societe = $this->facture->getSociete();
        $code_journal = FactureConfiguration::getInstance()->getCodeJournal();

        $date_facturation = DateTime::createFromFormat("Y-m-d",$this->facture->date_facturation)->format("d/m/Y");
        $facture = $this->facture;
        $csv = '';
        $csv_prefix = $facture->identifiant.";".$this->facture->declarant->nom.";".$facture->code_comptable_client.';'.$facture->numero_archive.";";
        if($facture->exist('paiements')) {
          foreach ($facture->paiements as $paiement) {
              if ($this->que_les_non_verses_comptablement && $paiement->versement_comptable) {
                  continue;
              }
              if ($date_max && $date_max < $paiement->date) {
                  continue;
              }
              if ($set_verse) {
                  $paiement->versement_comptable = true;
              }
              $csv .= $csv_prefix;
              $csv .= $paiement->date.";";
              $csv .= $this->floatHelper->formatFr($paiement->montant,2,2).";";
              $csv .= $paiement->type_reglement.";";
              $csv .= $paiement->commentaire.";";
              $csv .= $this->floatHelper->formatFr($facture->total_ttc - $facture->montant_paiement,2,2).';';
              $csv .= $paiement->exist('execute') ? $paiement->execute.";" : ";";
              $csv .= $paiement->versement_comptable.";";
              $csv .= $facture->_id.";";
              $csv .= $paiement->getHash().';';
              $csv .= $code_journal.';';
              $csv .= $paiement->getNumeroRemise().';';
              $csv .= '4110000;';
              $csv .= "\n";
          }
        }

        return $csv;
    }

}
