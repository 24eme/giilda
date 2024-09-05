<?php

class ExportFactureCSV_declarvin {

    const TYPE_LIGNE_LIGNE = 'LIGNE';
    const TYPE_LIGNE_ECHEANCE = 'ECHEANCE';
    const TYPE_LIGNE_TVA = 'TVA';
    const CODE_TVA = 'C03';

    public function __construct($ht = false) {
    	$this->ht = $ht;
    }

    public static function printHeader() {
        echo self::printHeaderBase() . "\n";
    }

    private static function printHeaderBase() {
        echo "code journal;date;date de saisie;numero de facture;libelle;compte general;compte tiers;compte analytique;date echeance;sens;montant;piece;reference;id couchdb;type ligne;nom client;code comptable client;origine type;produit type;origine id; volume; cvo; code tva; numero de facture; id produit export;id societe; reglement; numero adherent ; avoir numero facture";
    }

    public function printFacture($doc_or_id, $export_annee_comptable = false) {

        if ($doc_or_id instanceof Facture) {
            $facture = $doc_or_id;
        } else {
            $facture = FactureClient::getInstance()->find($doc_or_id);
        }
        $prefix_sage = FactureConfiguration::getInstance($facture->getOrAdd('interpro'))->getPrefixSage();
        $compte_general = FactureConfiguration::getInstance($facture->getOrAdd('interpro'))->getGeneralCompte();
        $reglement = ($facture->getNbPaiementsAutomatique() > 0)? 'PRELEVEMENT' : 'CHEQUE';

        if(FactureConfiguration::getInstance($facture->getOrAdd('interpro'))->getPrefixSageDivers() && $facture->isFactureDivers()) {
            $prefix_sage = FactureConfiguration::getInstance($facture->getOrAdd('interpro'))->getPrefixSageDivers();
        }

        $factureAvoir = null;
        if($facture->exist('avoir') && $facture->avoir) {
            $factureAvoir = FactureClient::getInstance()->find($facture->avoir);
        }

        if (!$facture) {
            echo sprintf("WARNING;Le document n'existe pas %s\n", $doc_or_id);
            return;
        }
        $societe = null;
        foreach ($facture->lignes as $t => $lignes) {
            $origine_mvt = "";
            foreach ($lignes->origine_mouvements as $keyDoc => $mvt) {
                $origine_mvt = $keyDoc;
            }
            foreach ($lignes->details as $detail) {
                $code_compte = ($detail->exist('code_compte') && $detail->code_compte) ? $detail->code_compte : FactureConfiguration::getInstance($facture->getOrAdd('interpro'))->getDefautCompte();
                $identifiant_analytique = ($detail->exist('identifiant_analytique') && $detail->identifiant_analytique)? $detail->identifiant_analytique : $detail->identifiant_analytique;
		        $libelle = $lignes->libelle.' - '.$detail->libelle;
                $idProduitExport = md5($detail->libelle);
                echo $prefix_sage.';' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';' . $libelle
                . ';'.$code_compte.';;' . $identifiant_analytique . ';;' . $this->getSens($detail->montant_ht, "CREDIT") . ';' . $this->getMontant($detail->montant_ht, "CREDIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_LIGNE . ';' . $facture->declarant->nom . ";" . $facture->code_comptable_client . ';' . $detail->origine_type . ';' . "PRODUIT_TYPE" . ';' . $origine_mvt . ';' . $detail->quantite . ';' . $detail->prix_unitaire
                . ";".self::CODE_TVA.";".$facture->numero_piece_comptable.";".$idProduitExport.";".$facture->identifiant.";;".$facture->numero_adherent.";".($factureAvoir) ? $factureAvoir->numero_piece_comptable : null;

                echo "\n";
            }
        }
	if (!$this->ht) {
	        echo $prefix_sage.';' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';' . $facture->numero_piece_comptable . ' - '.Date::francizeDate($facture->date_facturation).' - '.$facture->declarant->nom. ';' . $this->getSageCompteGeneral($facture) . ';;;;' . $this->getSens($facture->taxe, "CREDIT") . ';' . $this->getMontant($facture->taxe, "CREDIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_TVA . ';' . $facture->declarant->nom . ";" . $facture->code_comptable_client . ";;;;;;".self::CODE_TVA.";".$facture->numero_piece_comptable.";";
	        echo "\n";
	}

        $nbecheance = count($facture->echeances);
        if ($nbecheance) {
            $i = 0;
            foreach ($facture->echeances as $e) {
                $i++;
                echo $prefix_sage.';' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';' . $facture->numero_piece_comptable . ' - Echeance ' . $i . '/' . $nbecheance . ' - '.$facture->declarant->nom.';'.$compte_general.';' . $facture->code_comptable_client . ';;' . $e->echeance_date . ';' . $this->getSens($e->montant_ttc, "DEBIT") . ';' . $this->getMontant($e->montant_ttc, "DEBIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . $facture->code_comptable_client . ";;;;;;".self::CODE_TVA.";".$facture->numero_piece_comptable.";;;".$reglement;

                echo "\n";
            }
        } else {
            echo $prefix_sage.';' . $facture->date_facturation . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';' . $facture->numero_piece_comptable . ' - '.Date::francizeDate($facture->date_facturation).' - '.$facture->declarant->nom.';'.$compte_general.';' . $facture->code_comptable_client . ';;' . $facture->date_echeance . ';' . $this->getSens($facture->total_ttc, "DEBIT") . ';' . $this->getMontant($facture->total_ttc, "DEBIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . $facture->code_comptable_client . ";;;;;;".self::CODE_TVA.";".$facture->numero_piece_comptable.";;;".$reglement;

            echo "\n";
        }
    }

    protected function getSageCompteGeneral($facture) {
        if ($facture->getTauxTva() == 20.0) {
            return FactureConfiguration::getInstance($facture->getOrAdd('interpro'))->getTVACompte();
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
