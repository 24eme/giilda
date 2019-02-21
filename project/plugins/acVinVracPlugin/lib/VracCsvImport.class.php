<?php

class VracCsvImport extends CsvFile {

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

    const LABEL_BIO = 'agriculture_biologique';

    public static $labels_array = [self::LABEL_BIO => "Agriculture Biologique"];

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
        $lines = $this->getCsv();

        foreach ($lines as $line) {
            yield $line;
        }
    }

    /**
     * Importe des vracs dans la base
     *
     * @return int Nombre de vracs importés
     */
    public function import() {
        $configuration = ConfigurationClient::getInstance()->getCurrent();

        foreach ($this->getLines() as $line) {
            $v = new Vrac();

            $v->date_signature = $line[self::CSV_DATE_SIGNATURE];
            $v->date_campagne = $v->date_signature;

            $v->valide->date_saisie = $line[self::CSV_DATE_SAISIE];
            $v->date_visa = $v->valide->date_saisie;

            $v->numero_contrat = $line[self::CSV_NUMERO_CONTRAT];
            $v->numero_archive = $line[self::CSV_NUMERO_PAPIER];

            $v->vendeur_identifiant = $line[self::CSV_VENDEUR_ID];
            $v->acheteur_identifiant = $line[self::CSV_ACHETEUR_ID];
            $v->representant_identifiant = ($line[self::CSV_INTERMEDIAIRE_ID]) ?: $v->vendeur_identifiant;

            $v->mandataire_exist = false;
            if ($line[self::CSV_COURTIER_ID]) {
                $v->mandataire_identifiant = $line[self::CSV_COURTIER_ID];
                $v->mandataire_exist = true;
            }

            $v->vendeur->cvi = ($line[self::CSV_VENDEUR_CVI]) ?: null;

            $v->responsable = ($line[self::CSV_RESPONSABLE]) ?: null;

            $produit = $configuration->identifyProductByLibelle($line[self::CSV_PRODUIT_LIBELLE]);

            if ($produit) {
                $v->setProduit($produit->getHash());
            }

            $v->cepage_libelle = ($line[self::CSV_CEPAGE_LIBELLE]) ?: null;

            $v->type_transaction = ($line[self::CSV_TYPE_TRANSACTION]) ?: null;
            $v->millesime = ($line[self::CSV_MILLESIME]) ? (int) $line[self::CSV_MILLESIME] : null;
            $v->categorie_vin = ($line[self::CSV_CATEGORIE_VIN]) ?: VracClient::CATEGORIE_VIN_GENERIQUE;
            $v->domaine = ($line[self::CSV_CATEGORIE_VIN_INFO]) ? trim($line[self::CSV_CATEGORIE_VIN_INFO]) : null;
            $v->degre = ($line[self::CSV_DEGRE]) ? trim($line[self::CSV_DEGRE]) : null;

            $v->bouteilles_contenance_volume = ($line[self::CSV_RECIPIENT_CONTENANCE]) ?: null;
            $v->bouteilles_contenance_libelle = ($line[self::CSV_RECIPIENT_CONTENANCE_LIBELLE]) ?: null;

            $v->jus_quantite = $line[self::CSV_VOLUME_PROPOSE];
            $v->volume_propose = $line[self::CSV_VOLUME_PROPOSE];
            $v->volume_enleve = $line[self::CSV_VOLUME_PROPOSE];

            $v->prix_initial_unitaire = $line[self::CSV_PRIX_UNITAIRE_HL];
            $v->prix_initial_unitaire_hl = $v->prix_initial_unitaire;

            $v->date_debut_retiraison = $line[self::CSV_RETIRAISON_DATE_DEBUT];
            $v->date_limite_retiraison = $line[self::CSV_RETIRAISON_DATE_FIN];

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

            $v->delai_paiement = $line[self::CSV_CLE_DELAI_PAIEMENT];
            $v->delai_paiement_libelle = $line[self::CSV_DELAI_PAIEMENT];

            $v->moyen_paiement = $line[self::CSV_CLE_DELAI_PAIEMENT];
            $v->moyen_paiement_libelle = $line[self::CSV_MODE_PAIEMENT];

            $v->acompte = $line[self::CSV_ACOMPTE_SIGNATURE];

            $v->taux_courtage = ($line[self::CSV_TAUX_COURTAGE]) ?: null;
            $v->courtage_repartition = ($line[self::CSV_REPARTITION_COURTAGE]) ?: null;

            $v->clause_reserve_propriete = (preg_match('/clause_reserve_propriete/', $line[self::CSV_CLAUSES])) ? true : false;
            $v->autorisation_nom_vin = (preg_match('/autorisation_nom_vin/', $line[self::CSV_CLAUSES])) ? true : false;
            $v->autorisation_nom_producteur = (preg_match('/autorisation_nom_producteur/', $line[self::CSV_CLAUSES])) ? true : false;

            $v->conditionnement_crd = $line[self::CSV_CONDITIONNEMENT];
            $v->embouteillage = $line[self::CSV_EMBOUTEILLAGE];
            $v->preparation_vin = $line[self::CSV_PREPARATION_VIN];
            $v->commentaire = str_replace('\n', "\n", $line[self::CSV_COMMENTAIRE]);

            $v->cvo_repartition = ($line[self::CVO_REPARTITION]) ?: null;

            $v->versement_fa = VracClient::VERSEMENT_FA_TRANSMIS;

            $v->valide->statut = $line[self::CSV_STATUT];

            $v->constructId();
            $v->update();

            $validator = new VracValidation($v);

            if ($validator->hasErrors()) {
                foreach ($validator->getErrors() as $err) {
                    $this->errors[] = ['message' => $err->getMessage()];
                }
            }

            if (count($this->errors)) {
                throw new sfException('Erreur dans le fichier, au num. de contrat '. $line[self::CSV_NUMERO_CONTRAT]);
            }

            $v->save();

            self::$imported++;
        }
    }
}
