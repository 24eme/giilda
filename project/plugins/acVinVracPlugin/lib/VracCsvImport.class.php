<?php

class VracCsvImport extends CsvFile {

    const CSV_CAMPAGNE = 0;
    const CSV_NUMERO_CONTRAT = 1;
    const CSV_NUMERO_ARCHIVE = 2;
    const CSV_ETAPE = 3;
    const CSV_INTERNE = 4;
    const CSV_RESPONSABLE = 5;
    const CSV_TELEDECLARE = 6;
    const CSV_ACOMPTE = 7;
    const CSV_LOT = 8;
    const CSV_SURFACE = 9;
    const CSV_DEGRE = 10;
    const CSV_DELAI_PAIEMENT = 11;
    const CSV_MOYEN_PAIEMENT = 12;
    const CSV_DATE_LIMITE_RETIRAISON = 13;
    const CSV_DATE_DEBUT_RETIRAISON = 14;
    const CSV_CONDITIONS_PARTICULIERES = 15;
    const CSV_TVA = 16;
    const CSV_PLURIANNUEL = 17;
    const CSV_CLAUSE_RESERVE_PROPRIETE = 18;
    const CSV_AUTORISATION_NOM_VIN = 19;
    const CSV_AUTORISATION_NOM_PRODUCTEUR = 20;
    const CSV_CLAUSES = 21;
    const CSV_MILLESIME = 22;
    const CSV_CEPAGE = 23;
    const CSV_PREPARATION_VIN = 24;
    const CSV_EMBOUTEILLAGE = 25;
    const CSV_CONDITIONNEMENT_CRD = 26;
    const CSV_REFERENCE_CONTRAT = 27;
    const CSV_ANNEE_CONTRAT = 28;
    const CSV_SEUIL_REVISION = 29;
    const CSV_POURCENTAGE_VARIATION = 30;
    const CSV_CAHIER_CHARGE = 31;
    const CSV_UNITES = 32;
    const CSV_INTERLOCUTEUR_COMMERCIAL = 33;
    const CSV_CREATEUR_IDENTIFIANT = 34;
    const CSV_VENDEUR_ID = 35;
    const CSV_REPRESENTANT_ID = 36;
    const CSV_ACHETEUR_ID = 37;
    const CSV_MANDATAIRE_EXIST = 38;
    const CSV_MANDATANT = 39;
    const CSV_MANDATAIRE_ID = 40;
    const CSV_LOGEMENT = 41;
    const CSV_ATTENTE_ORIGINAL = 42;
    const CSV_TYPE_TRANSACTION = 43;
    const CSV_PRODUIT = 44;
    const CSV_CATEGORIE_VIN = 45;
    const CSV_DOMAINE = 46;
    const CSV_LABELS = 47;
    const CSV_RAISIN_QUANTITE = 48;
    const CSV_JUS_QUANTITE = 49;
    const CSV_BOUTEILLES_QUANTITE = 50;
    const CSV_BOUTEILLES_CONTENANCE_VOLUME = 51;
    const CSV_PRIX_INITIAL_UNITAIRE = 52;
    const CSV_PRIX_INITIAL_UNITAIRE_HL = 53;
    const CSV_PRIX_INITIAL_TOTAL = 54;
    const CSV_PRIX_UNITAIRE = 55;
    const CSV_PRIX_UNITAIRE_HL = 56;
    const CSV_PRIX_TOTAL = 57;
    const CSV_TYPE_CONTRAT = 58;
    const CSV_PRIX_VARIABLE = 59;
    const CSV_PART_VARIABLE = 60;
    const CSV_CVO_NATURE = 61;
    const CSV_CVO_REPARTITION = 62;
    const CSV_COURTAGE_REPARTITION = 63;
    const CSV_TAUX_COURTAGE = 64;
    const CSV_TAUX_REPARTITION = 65;
    const CSV_COMMENTAIRE = 66;
    const CSV_VERSEMENT_FA = 67;
    const CSV_DATE_CAMPAGNE = 68;
    const CSV_DATE_SIGNATURE = 69;
    const CSV_DATE_VISA = 70;
    const CSV_VOLUME_INITIAL = 71;
    const CSV_VOLUME_PROPOSE = 72;
    const CSV_VOLUME_ENLEVE = 73;
    const CSV_ENLEVEMENT_DATE = 74;
    const CSV_ENLEVEMENT_FRAIS_GARDE = 75;
    const CSV_VALIDE_DATE_SAISIE = 76;
    const CSV_VALIDE_STATUT = 77;
    const CSV_VALIDE_IDENTIFIANT = 78;
    const CSV_VALIDE_DATE_SIGNATURE_VENDEUR = 79;
    const CSV_VALIDE_DATE_SIGNATURE_ACHETEUR = 80;
    const CSV_VALIDE_DATE_SIGNATURE_COURTIER = 81;

    /*
    const CSV_NUMERO_PAPIER = 0;
    const CSV_NUMERO_CONTRAT = 1;
    const CSV_DATE_SIGNATURE = 2;
    const CSV_DATE_SAISIE = 3;
    const CSV_TYPE_TRANSACTION = 4;
    const CSV_STATUT = 5;
    const CSV_VENDEUR_ID = 6;
    const CSV_VENDEUR_CVI = 7;
    const CSV_VENDEUR_VIN_LOGEMENT_AUTRE = 8;
    const CSV_INTERMEDIAIRE_ID = 9;
    const CSV_ACHETEUR_ID = 10;
    const CSV_COURTIER_ID = 11;
    const CSV_RESPONSABLE = 12;
    const CSV_PRODUIT_ID = 13;
    const CSV_PRODUIT_LIBELLE = 14;
    const CSV_MILLESIME = 15;
    const CSV_CEPAGE_ID = 16;
    const CSV_CEPAGE_LIBELLE = 17;
    const CSV_CATEGORIE_VIN = 18;
    const CSV_CATEGORIE_VIN_INFO = 19;
    const CSV_SURFACE = 20;
    const CSV_LOT = 21;
    const CSV_DEGRE = 22;
    const CSV_RECIPIENT_CONTENANCE = 23;
    const CSV_RECIPIENT_CONTENANCE_LIBELLE = 24;
    const CSV_QUANTITE = 25;
    const CSV_QUANTITE_UNITE = 26;
    const CSV_VOLUME_PROPOSE = 27;
    const CSV_VOLUME_ENLEVE = 28;
    const CSV_PRIX_UNITAIRE = 29;
    const CSV_PRIX_UNITAIRE_HL = 30;
    const CSV_CLE_DELAI_PAIEMENT = 31;
    const CSV_DELAI_PAIEMENT = 32;
    const CSV_CLE_MODE_PAIEMENT = 33;
    const CSV_MODE_PAIEMENT = 34;
    const CSV_ACOMPTE_SIGNATURE = 35;
    const CSV_TAUX_COURTAGE = 36;
    const CSV_REPARTITION_COURTAGE = 37;
    const CSV_REPARTITION_CVO = 38;
    const CSV_RETIRAISON_DATE_DEBUT = 39;
    const CSV_RETIRAISON_DATE_FIN = 40;
    const CSV_CONDITIONNEMENT = 41;
    const CSV_EMBOUTEILLAGE = 42;
    const CSV_PREPARATION_VIN = 43;
    const CSV_CLAUSES = 44;
    const CSV_LABELS = 45;
    const CSV_COMMENTAIRE = 46;
    const CVO_REPARTITION = 47;
     */

    const LABEL_BIO = 'agriculture_biologique';

    public static $labels_array = [self::LABEL_BIO => "Agriculture Biologique"];

    /** @var int $imported Nombre de vrac importé */
    protected static $imported = 0;

    /** @var array $errors Tableau des erreurs de l'import */
    private $errors = [];

    /**
     * Crée une instance depuis un tableau CSV
     *
     * @param array $array Le CSV transformé en tableau
     * @return self
     */
    public static function createFromArray(array $lines) {
        $class = new self();
        $class->csvdata = $lines;

        return $class;
    }

    /**
     * Générateur qui renvoie les lignes du CSV une à une
     *
     * @yield array $line Un vrac
     */
    public function getLines() {
        foreach ($this->csvdata as $line) {
            yield $line;
        }
    }

    /**
     * Importe des vracs dans la base
     *
     * @param bool $verified Le csv a été vérifier
     * @return int|array Nombre de vracs importés ou tableau d'erreur
     */
    public function import($verified = false) {
        $configuration = ConfigurationClient::getInstance()->getCurrent();

        foreach ($this->getLines() as $line) {
            $v = new Vrac();

            $v->date_signature = $line[self::CSV_DATE_SIGNATURE];
            $v->date_campagne = $v->date_signature;

            $v->valide->date_saisie = $line[self::CSV_VALIDE_DATE_SAISIE];
            $v->date_visa = $v->valide->date_saisie;

            $v->campagne = $line[self::CSV_CAMPAGNE];
            $v->numero_contrat = $line[self::CSV_NUMERO_CONTRAT];
            $v->numero_archive = $line[self::CSV_NUMERO_ARCHIVE];

            $v->vendeur_identifiant = $line[self::CSV_VENDEUR_ID];
            $v->acheteur_identifiant = $line[self::CSV_ACHETEUR_ID];
            $v->representant_identifiant = ($line[self::CSV_REPRESENTANT_ID]) ?: $v->vendeur_identifiant;

            $v->mandataire_exist = false;
            if ($line[self::CSV_MANDATAIRE_ID]) {
                $v->mandataire_identifiant = $line[self::CSV_MANDATAIRE_ID];
                $v->mandataire_exist = true;
            }

            $v->responsable = ($line[self::CSV_RESPONSABLE]) ?: null;

            $produit = $configuration->identifyProductByLibelle($line[self::CSV_PRODUIT]);

            if ($produit) {
                $v->setProduit($produit->getHash());
            }

            $v->cepage = ($line[self::CSV_CEPAGE]) ?: null;

            $v->type_transaction = ($line[self::CSV_TYPE_TRANSACTION]) ?: null;
            $v->millesime = ($line[self::CSV_MILLESIME]) ? (int) $line[self::CSV_MILLESIME] : null;
            $v->categorie_vin = ($line[self::CSV_CATEGORIE_VIN]) ?: VracClient::CATEGORIE_VIN_GENERIQUE;
            $v->domaine = ($line[self::CSV_DOMAINE]) ? trim($line[self::CSV_DOMAINE]) : null;
            $v->degre = ($line[self::CSV_DEGRE]) ? trim($line[self::CSV_DEGRE]) : null;

            $v->bouteilles_contenance_volume = ($line[self::CSV_BOUTEILLES_CONTENANCE_VOLUME]) ?: null;
            //$v->bouteilles_contenance_libelle = ($line[self::CSV_BOUTEILLES_CONTENANCE_LIBELLE]) ?: null;

            $v->jus_quantite = $line[self::CSV_VOLUME_PROPOSE];
            $v->volume_propose = $line[self::CSV_VOLUME_PROPOSE];
            $v->volume_enleve = $line[self::CSV_VOLUME_PROPOSE];

            $v->prix_initial_unitaire = $line[self::CSV_PRIX_UNITAIRE_HL];
            $v->prix_initial_unitaire_hl = $v->prix_initial_unitaire;

            $v->date_debut_retiraison = $line[self::CSV_DATE_DEBUT_RETIRAISON];
            $v->date_limite_retiraison = $line[self::CSV_DATE_LIMITE_RETIRAISON];

            if ($line[self::CSV_LABELS]) {
                $labels = explode(',', $line[self::CSV_LABELS]);
                foreach ($labels as $label_key) {
                    $label_key = trim($label_key);
                    if (array_key_exists($label_key, self::$labels_array)) {
                        $v->getOrAdd('label')->add($label_key, self::$labels_array[$label_key]);
                    }
                }
            }

            $v->vendeur_tva = (preg_match('/assujetti_tva/', $line[self::CSV_CLAUSES])) ? 1 : 0;
            $v->tva = (preg_match('/facturation_tva/', $line[self::CSV_CLAUSES])) ? 'AVEC' : 'SANS';

            $v->delai_paiement = $line[self::CSV_DELAI_PAIEMENT];

            $v->moyen_paiement = $line[self::CSV_MOYEN_PAIEMENT];

            $v->acompte = $line[self::CSV_ACOMPTE];

            $v->taux_courtage = ($line[self::CSV_TAUX_COURTAGE]) ?: null;
            $v->courtage_repartition = ($line[self::CSV_TAUX_REPARTITION]) ?: null;

            $v->clause_reserve_propriete = (preg_match('/clause_reserve_propriete/', $line[self::CSV_CLAUSES])) ? true : false;
            $v->autorisation_nom_vin = (preg_match('/autorisation_nom_vin/', $line[self::CSV_CLAUSES])) ? true : false;
            $v->autorisation_nom_producteur = (preg_match('/autorisation_nom_producteur/', $line[self::CSV_CLAUSES])) ? true : false;

            $v->conditionnement_crd = $line[self::CSV_CONDITIONNEMENT_CRD];
            $v->embouteillage = $line[self::CSV_EMBOUTEILLAGE];
            $v->preparation_vin = $line[self::CSV_PREPARATION_VIN];
            $v->commentaire = str_replace('\n', "\n", $line[self::CSV_COMMENTAIRE]);

            $v->cvo_repartition = ($line[self::CSV_CVO_REPARTITION]) ?: null;

            $v->versement_fa = VracClient::VERSEMENT_FA_TRANSMIS;

            $v->valide->statut = $line[self::CSV_VALIDE_STATUT];

            $v->constructId();

            if ($verified) {
                $v->update();
                $v->save();

                self::$imported++;
            } else {
                $validator = new VracValidation($v);

                if ($validator->hasErreurs()) {
                    foreach ($validator->getErreurs() as $err) {
                        $this->errors[$line[self::CSV_NUMERO_CONTRAT]][] = $err->getMessage() . ': ' . $err->getInfo();
                    }
                }
            }
        }

        return ($verified) ? self::$imported : $this->errors;
    }
}
