<?php

class ExportCSV {

    const TYPE_LIGNE_LIGNE = 'LIGNE';
    const TYPE_LIGNE_ECHEANCE = 'ECHEANCE';
    const TYPE_LIGNE_TVA = 'TVA';

    public function __construct() {
        
    }

    public static function printHeaderAnneeComptable() {
        echo self::printHeaderBase() . ";code postal;commune;type societe\n";
    }

    public static function printHeader() {
        echo self::printHeaderBase() . "\n";
    }

    private static function printHeaderBase() {
        echo "code journal;date;date de saisie;numero de facture;libelle;compte general;compte tiers;compte analytique;date echeance;sens;montant;piece;reference;id couchdb;type ligne;nom client;code comptable client;origine type;produit type;origine id; volume; cvo";
    }

    public function printFacture($doc_or_id, $export_annee_comptable = false) {

        if ($doc_or_id instanceof Facture) {
            $facture = $doc_or_id;
        } else {
            $facture = FactureClient::getInstance()->find($doc_or_id);
        }

        if (!$facture) {
            echo sprintf("WARNING;Le document n'existe pas %s\n", $doc_or_id);
            return;
        }
        $societe = null;
        if ($export_annee_comptable) {
            $societe = SocieteClient::getInstance()->find($facture->identifiant);
        }
        foreach ($facture->lignes as $t => $lignes) {
            foreach ($lignes as $l) {
                echo 'VEN;' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_interloire . ';Facture n°' . $facture->numero_interloire . ' (' . $l->produit_libelle
                . ');70610000;;' . $l->produit_identifiant_analytique . ';;CREDIT;' . $l->montant_ht . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_LIGNE . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ';' . $l->origine_type . ';' . $l->produit_type . ';' . $l->origine_identifiant . ';' . $l->volume . ';' . $l->cotisation_taux
                . ";";
                if ($export_annee_comptable) {
                    echo $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";";
                }

                echo "\n";
            }
        }
        echo 'VEN;' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_interloire . ';Facture n°' . $facture->numero_interloire . ' (TVA);' . $this->getSageCompteGeneral($facture) . ';;;;CREDIT;' . $facture->taxe . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_TVA . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ";;;;;;";
        if ($export_annee_comptable) {
            echo $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";";
        }

        echo "\n";
        $nbecheance = count($facture->echeances);
        if ($nbecheance) {
            $i = 0;
            foreach ($facture->echeances as $e) {
                $i++;
                echo 'VEN;' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_interloire . ';Facture n°' . $facture->numero_interloire . ' (Echeance ' . ($nbecheance - $i + 1) . '/' . $nbecheance . ');41100000;' . sprintf("%08d", $facture->code_comptable_client) . ';;' . $e->echeance_date . ';DEBIT;' . $e->montant_ttc . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ";;;;;;";
                if ($export_annee_comptable) {
                    echo $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";";
                }

                echo "\n";
            }
        } else {
            echo 'VEN;' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_interloire . ';Facture n°' . $facture->numero_interloire . ' (Echeance unique);41100000;' . sprintf("%08d", $facture->code_comptable_client) . ';;' . $facture->date_facturation . ';DEBIT;' . $facture->total_ttc . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ";;;;;;";
            if ($export_annee_comptable) {
                echo $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";";
            }

            echo "\n";
        }
    }

    protected function getSageCompteGeneral($facture) {
        if ($facture->getTauxTva() == 20.0) {
            return "44570100";
        }

        return "44570000";
    }

}
