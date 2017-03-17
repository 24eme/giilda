<?php

class ExportFactureCSV_ivso {

    const TYPE_LIGNE_LIGNE = 'LIGNE';
    const TYPE_LIGNE_ECHEANCE = 'ECHEANCE';
    const TYPE_LIGNE_TVA = 'TVA';

    public function __construct($ht = false) {
    	$this->ht = $ht;
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
        $prefix_sage = FactureConfiguration::getInstance()->getPrefixSage();

        if(FactureConfiguration::getInstance()->getPrefixSageDivers() && $facture->isFactureDivers()) {
            $prefix_sage = FactureConfiguration::getInstance()->getPrefixSageDivers();
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
            $origine_mvt = "";
            foreach ($lignes->origine_mouvements as $keyDoc => $mvt) {
                $origine_mvt = $keyDoc;
            }

            foreach ($lignes->details as $detail) {
                $code_compte = ($detail->exist('code_compte') && $detail->code_compte) ? $detail->code_compte : FactureConfiguration::getInstance()->getDefautCompte();
                $identifiant_analytique = ($detail->exist('identifiant_analytique') && $detail->identifiant_analytique)? $detail->identifiant_analytique : $detail->identifiant_analytique;

		$libelle = $lignes->libelle.' - '.$detail->libelle;
                echo $prefix_sage.';' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';' . $libelle
                . ';'.$code_compte.';;' . $identifiant_analytique . ';;' . $this->getSens($detail->montant_ht, "CREDIT") . ';' . $this->getMontant($detail->montant_ht, "CREDIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_LIGNE . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ';' . $detail->origine_type . ';' . "PRODUIT_TYPE" . ';' . $origine_mvt . ';' . $detail->quantite . ';' . $detail->prix_unitaire
                . ";";
                if ($export_annee_comptable) {
                    echo $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";";
                }

                echo "\n";
            }
        }
	if (!$this->ht) {
	        echo $prefix_sage.';' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';' . $facture->numero_piece_comptable . ' - '.Date::francizeDate($facture->date_facturation).' - '.$facture->declarant->nom. ';' . $this->getSageCompteGeneral($facture) . ';;;;' . $this->getSens($facture->taxe, "CREDIT") . ';' . $this->getMontant($facture->taxe, "CREDIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_TVA . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ";;;;;;";
	        if ($export_annee_comptable) {
        	    echo $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";";
	        }
	        echo "\n";
	}

        $nbecheance = count($facture->echeances);
        if ($nbecheance) {
            $i = 0;
            foreach ($facture->echeances as $e) {
                $i++;
                echo $prefix_sage.';' . $e->date . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';Facture ' . $facture->numero_piece_comptable . ' (Echeance ' . ($nbecheance - $i + 1) . '/' . $nbecheance . ');41100000;' . sprintf("%08d", $facture->code_comptable_client) . ';;' . $e->echeance_date . ';' . $this->getSens($e->montant_ttc, "DEBIT") . ';' . $this->getMontant($e->montant_ttc, "DEBIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ";;;;;;";
                if ($export_annee_comptable) {
                    echo $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";";
                }

                echo "\n";
            }
        } else {
            echo $prefix_sage.';' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';' . $facture->numero_piece_comptable . ' - '.Date::francizeDate($facture->date_facturation).' - '.$facture->declarant->nom.';41100000;' . sprintf("%08d", $facture->code_comptable_client) . ';;' . $facture->date_echeance . ';' . $this->getSens($facture->total_ttc, "DEBIT") . ';' . $this->getMontant($facture->total_ttc, "DEBIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ";;;;;;";
            if ($export_annee_comptable) {
                echo $societe->siege->code_postal . ";" . $societe->siege->commune . ";" . $societe->type_societe . ";";
            }

            echo "\n";
        }
    }

    protected function getSageCompteGeneral($facture) {
        if ($facture->getTauxTva() == 20.0) {
            return FactureConfiguration::getInstance()->getTVACompte();
        }

        return "44570000";
    }

    public function getSens($montant, $sens) {

        return $sens;
    }

    public function getMontant($montant, $sens) {

        return $montant;
    }
}
