<?php

class ExportFacturePaiementsCSV {

    protected $facture = null;
    protected $header = false;

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
        return "Identifiant;Raison Sociale;Code comptable client;Numero facture;Date de paiement;Montant;Type de reglement;Commentaire;Montant restant a payer;Execute;Exporte;Facture doc ID;Paiement ID;Date facture;Sens;Code journal;Numero remise; Numero compte\n";
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
        $general_compte = FactureConfiguration::getInstance()->getGeneralCompte();
        $banque_compte = FactureConfiguration::getInstance()->getBanqueCompte();

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
              $numRemise = $paiement->getNumeroRemise();
              $csv .= $csv_prefix;
              $csv .= $paiement->date.";";
              $csv .= round($paiement->montant,2).";";
              $csv .= $paiement->type_reglement.";";
              $csv .= $paiement->commentaire.";";
              $csv .= round($facture->total_ttc - $facture->montant_paiement,2).';';
              $csv .= $paiement->exist('execute') ? $paiement->execute.";" : ";";
              $csv .= $paiement->versement_comptable.";";
              $csv .= $facture->_id.";";
              $csv .= $paiement->getHash().';';
              $csv .= $facture->date_facturation.';';
              $csv .= 'CREDIT;';
              $csv .= $code_journal.';';
              $csv .= $numRemise.';';
              $csv .= $general_compte.';';
              $csv .= "\n";
              if ($numRemise && $banque_compte) {
                  $csv .= $csv_prefix;
                  $csv .= $paiement->date.";";
                  $csv .= round($paiement->montant,2).";";
                  $csv .= $paiement->type_reglement.";";
                  $csv .= ";";
                  $csv .= ";";
                  $csv .= ";";
                  $csv .= ";";
                  $csv .= $facture->_id.";";
                  $csv .= $paiement->getHash().';';
                  $csv .= $facture->date_facturation.';';
                  $csv .= 'DEBIT;';
                  $csv .= $code_journal.';';
                  $csv .= $numRemise.';';
                  $csv .= $banque_compte.';';
                  $csv .= "\n";
              }
          }
        }

        return $csv;
    }

}
