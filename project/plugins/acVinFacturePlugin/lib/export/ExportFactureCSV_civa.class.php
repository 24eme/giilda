<?php

class ExportFactureCSV_civa {

    const TYPE_LIGNE_LIGNE = 'LIGNE';
    const TYPE_LIGNE_ECHEANCE = 'ECHEANCE';
    const TYPE_LIGNE_TVA = 'TVA';
    const CODE_TVA = '';

    public function __construct($ht = false) {
    	$this->ht = $ht;
    }

    public static function printHeader() {
        echo self::printHeaderBase() . "\n";
    }

    private static function printHeaderBase() {
        echo "code journal;date;date de saisie;numero de facture;libelle;compte general;compte tiers;compte analytique;date echeance;sens;montant;piece;reference;id couchdb;type ligne;nom client;code comptable client;origine type;produit type;origine id; volume; cvo; code tva; num piece; mode de reglement";
    }

    public function printFacture($doc_or_id, $export_annee_comptable = false) {
        sfApplicationConfiguration::getActive()->loadHelpers(array('Date'));
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
        $aggregateLines = array();
        $code_compte = FactureConfiguration::getInstance()->getDefautCompte();
        $identifiant_analytique = null;
        $libelles = array();
        foreach ($facture->lignes as $t => $lignes) {
            $origine_mvt = null;
            foreach ($lignes->origine_mouvements as $keyDoc => $mvt) {
                $origine_mvt = $keyDoc;
            }
            $periode = strtoupper(str_replace('-', ' ', KeyInflector::slugify(format_date(substr((explode('-', $t))[2],0,4).'-'.substr((explode('-', $t))[2],-2).'-01', 'MMM yy', 'fr_FR'))));
            $libelle = 'FACTURATION '.substr(explode(" ", $periode)[0], 0, 3).' '.explode(" ", $periode)[1];
            $libelles[$periode] = $periode;
            if (!isset($aggregateLines[$origine_mvt])) {
                $aggregateLines[$origine_mvt] = array(
                    $prefix_sage,
                    $facture->date_emission,
                    $facture->date_emission,
                    null,
                    $libelle,
                    $code_compte,
                    null,
                    $identifiant_analytique,
                    null,
                    $this->getSens($lignes->montant_ht, "CREDIT"),
                    $this->getMontant($lignes->montant_ht, "CREDIT"),
                    null,
                    $facture->numero_piece_comptable,
                    $facture->_id,
                    self::TYPE_LIGNE_LIGNE,
                    $facture->declarant->nom,
                    $facture->code_comptable_client,
                    null,
                    "PRODUIT_TYPE",
                    $origine_mvt,
                    $lignes->quantite,
                    $lignes->prix_unitaire,
                    self::CODE_TVA,
                    null,
                    null
                );
            } else {
                $aggregateLines[$origine_mvt][10] += $this->getMontant($lignes->montant_ht, "CREDIT");
                $aggregateLines[$origine_mvt][20] += $lignes->quantite;
            }
        }

        $annee = null;
        $previousPeriode = null;
        foreach($libelles as $periode => $libelle) {
            $libelles[$periode] = substr(explode(" ", $periode)[0], 0, 3);
            if(!is_null($annee) && $annee != explode(" ", $periode)[1] && $previousPeriode) {
                $libelles[$previousPeriode] .= ' '.explode(" ", $periode)[1];
            }
            $annee = explode(" ", $periode)[1];
            $previousPeriode = $periode;
        }
        if($previousPeriode) {
            $libelles[$previousPeriode] .= ' '.$annee;
        }
        foreach($aggregateLines as $aggregateLine) {
            echo implode(';', $aggregateLine).";\n";
        }
	if (!$this->ht) {
	        echo $prefix_sage.';' . $facture->date_emission . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';FACTURATION ' . implode('/',$libelles) . ';' . $this->getSageCompteGeneral($facture) . ';;;;' . $this->getSens($facture->taxe, "CREDIT") . ';' . $this->getMontant($facture->taxe, "CREDIT") . ';;;' . $facture->_id . ';' . self::TYPE_LIGNE_TVA . ';' . $facture->declarant->nom . ";" . sprintf("%08d", $facture->code_comptable_client) . ";;;;;;".self::CODE_TVA.";;";
	        echo "\n";
	}

        $nbecheance = count($facture->echeances);
        if ($nbecheance) {
            $i = 0;
            foreach ($facture->echeances as $e) {
                $i++;
                echo $prefix_sage.';' . $facture->date_emission . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';COTISATION ' . implode('/',$libelles) . ';411000;' . sprintf("%d", $facture->code_comptable_client) . ';;' . $e->echeance_date . ';' . $this->getSens($e->montant_ttc, "DEBIT") . ';' . $this->getMontant($e->montant_ttc, "DEBIT") . ';;'.$facture->numero_piece_comptable.';' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . sprintf("%d", $facture->code_comptable_client) . ";;;;;;".self::CODE_TVA.";;2";

                echo "\n";
            }
	} elseif ($facture->exist('paiements') && count($facture->paiements->toArray(true, false))){
		foreach ($facture->paiements as $p) {
		 	echo $prefix_sage.';' . $facture->date_emission . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';COTISATION ' . implode('/',$libelles) . ';411000;' . sprintf("%d", $facture->code_comptable_client) . ';;' . $p->date . ';' . $this->getSens($p->montant, "DEBIT") . ';' . $this->getMontant($p->montant, "DEBIT") . ';;'.$facture->numero_piece_comptable.';' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . sprintf("%d", $facture->code_comptable_client) . ";;;;;;".self::CODE_TVA.";;2";
            echo "\n";
		}
	} else {
            echo $prefix_sage.';' . $facture->date_emission . ';' . $facture->date_emission . ';' . $facture->numero_piece_comptable . ';COTISATION ' . implode('/',$libelles) .';411000;' . sprintf("%d", $facture->code_comptable_client) . ';;' . $facture->date_echeance . ';' . $this->getSens($facture->total_ttc, "DEBIT") . ';' . $this->getMontant($facture->total_ttc, "DEBIT") . ';;'.$facture->numero_piece_comptable.';' . $facture->_id . ';' . self::TYPE_LIGNE_ECHEANCE . ';' . $facture->declarant->nom . ";" . sprintf("%d", $facture->code_comptable_client) . ";;;;;;".self::CODE_TVA.";;3";
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
