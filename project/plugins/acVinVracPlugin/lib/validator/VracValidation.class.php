<?php

class VracValidation extends DocumentValidation
{
    private $etablissement_client;

    private $float_helper;

    /**
     * Constructeur
     *
     * @param Vrac $document Le vrac à vérifier
     * @param array $options Un tableau d'option
     */
    public function __construct(Vrac $document, $options = []) {
        $this->etablissement_client = EtablissementClient::getInstance();
        $this->float_helper = FloatHelper::getInstance();
        parent::__construct($document, $options);
    }

    /**
     * Configure les types de contrôles
     */
    public function configure() {
        parent::addControle('erreur', 'date', 'La date est mal formée');
        parent::addControle('erreur', 'numero', 'Mauvaise construction d\'identifiant');
        parent::addControle('erreur', 'doublon', 'L\'entrée existe déjà');
        parent::addControle('erreur', 'inexistant', 'L\'entrée n\'existe pas');
        parent::addControle('erreur', 'float', 'L\'entrée renseignée n\'est pas un float');
        parent::addControle('erreur', 'bouteille', 'Un problème avec les bouteilles');

        parent::addControle('vigilance', 'mandataire', '');
        parent::addControle('vigilance', 'date', '');
        parent::addControle('vigilance', 'domaine', '');
    }

    /**
     * Contrôle les entrées du vrac
     */
    public function controle() {
        if (! $this->checkDate($this->document->date_signature)) {
            parent::addPoint('erreur', 'date', 'La date de signature doit être renseignée');
        }

        if (! $this->checkDate($this->document->valide->date_saisie)) {
            parent::addPoint('erreur', 'date', 'La date de saisie doit être renseignée');
        }

        if (! $this->checkNumero($this->document->numero_contrat, 13)) {
            parent::addPoint('erreur', 'numero', 'Le numéro de contrat n\'est pas bon');
        }

        if (! $this->checkNumero($this->document->numero_archive, 5)) {
            parent::addPoint('erreur', 'numero', 'Le numéro d\'archive n\'est pas bon');
        }

        $isDoublon = VracClient::getInstance()->find($this->document->_id, acCouchdbClient::HYDRATE_JSON);

        if ($isDoublon) {
            parent::addPoint('erreur', 'doublon', 'Le numéro d\'archive existe déjà !');
        }

        if (! $this->checkEtablissement($this->document->vendeur_identifiant)) {
            parent::addPoint('erreur', 'inexistant', 'Le vendeur n\'existe pas');
        }

        if (! $this->checkEtablissement($this->document->acheteur_identifiant)) {
            parent::addPoint('erreur', 'inexistant', 'L\'acheteur n\'existe pas');
        }

        if ($this->document->representant_identifiant !== $this->document->vendeur_identifiant) {
            if (! $this->checkEtablissement($this->document->representant_identifiant)) {
                parent::addPoint('erreur', 'inexistant', 'Le représentant n\'existe pas');
            }
        }

        if ($this->document->taux_courtage && ! $this->checkFloat($this->document->taux_courtage)) {
            parent::addPoint('erreur', 'float', 'Le taux de courtage n\'est pas un chiffre floattant');
        }

        if ($this->mandataire_identifiant) {
            if (! $this->mandataire_exist) {
                parent::addPoint('vigilance', 'mandataire', 'Le flag de mandataire doit être à true');
            }

            if (! $this->checkEtablissement($this->document->mandataire_identifiant)) {
                parent::addPoint('erreur', 'inexistant', 'Le mandataire n\'existe pas');
            }

            if (! $this->document->taux_courtage || ! $this->document->courtage_repartition) {
                parent::addPoint('vigilance', 'mandataire', 'Le contrat n\'a pas de taux de courtage ou de la repartition alors qu\'il y a un courtier');
            }
        } else {
            if ($this->document->taux_courtage || $this->document->courtage_repartition) {
                parent::addPoint('vigilance', 'mandataire', 'Le contrat a du taux de courtage ou de la repartition alors qu\'il n\'y a pas de courtier');
            }
        }

        if ($this->document->responsable && !in_array($this->document->responsable, ['vendeur', 'mandataire', 'acheteur'])) {
            parent::addPoint('erreur', 'inexistant', 'Le type de responsable n\'existe pas');
        }

        if (! $this->produit) {
            parent::addPoint('erreur', 'inexistant', 'Le produit n\'a pas été trouvé');
        }

        if ($this->document->domaine) {
            if ($this->document->categorie_vin === VracClient::CATEGORIE_VIN_GENERIQUE) {
                parent::addPoint('vigilance', 'domaine', 'Un domaine a été spécifié alors que le vin est générique');
            }
        }

        if (! $this->checkFloat($this->document->degree)) {
            parent::addPoint('erreur', 'float', 'Le degré n\'est pas un chiffre flotant');
        }

        if ($this->document->bouteilles_contenance_volume) {
            if ($this->document->type_transaction !== VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE) {
                parent::addPoint('erreur', 'bouteille', 'Le contrat a une contenance de bouteille mais n\'a pas été signalé en contrat bouteille');
            }

            if (! $this->checkContenance($this->document->bouteilles_contenance_volume, $this->document->bouteilles_contenance_libelle)) {
                parent::addPoint('erreur', 'bouteille', 'La contenance n\'existe pas dans le configurateur');
            }
        }

        if ($this->document->type_transaction === VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE
            && !$this->document->bouteilles_contenance_volume) {
                parent::addPoint('erreur', 'bouteille', 'Le contrat a été signalé comme contrat bouteille, mais n\'a pas de contenance');
        }

        if (! $this->checkFloat($this->document->jus_quantite)) {
            parent::addPoint('erreur', 'float', 'La quantité n\'est pas un chiffre floattant');
        }

        if (! $this->document->prix_initial_unitaire || ! $this->document->prix_initial_unitaire_hl) {
            parent::addPoint('erreur', 'inexistant', 'Le prix unitaire est requis');
        }

        if (! $this->checkDate($this->document->date_limite_retiraison)) {
            parent::addPoint('erreur', 'date', 'La date de limite de retiraison n\'est pas valide');
        }

        if ($this->document->date_debut_retiraison) {
            if (! $this->checkDate($this->document->date_debut_retiraison)) {
                parent::addPoint('erreur', 'date', 'La date de début de retiraison n\'est pas valide');
            }

            if ($this->document->date_limite_retiraison < $this->document->date_debut_retiraison) {
                parent::addPoint('vigilance', 'date', 'La date de début de retiraison est supérieur à celle du début');
            }
        }

        if (! $this->checkFloat($this->document->acompte)) {
            parent::addPoint('erreur', 'float', 'L\'acompte n\'est pas un chiffre floattant');
        }

        if ($this->document->conditionnement_crd !== null) {
            $conditionnements = array_keys(VracConfiguration::getInstance()->getConditionnementsCRD());

            if (! in_array($this->document->conditionnement_crd, $conditionnements)) {
                parent::addPoint('erreur', 'inexistant', 'Le conditionnement n\'existe pas');
            }
        }

        if ($this->document->embouteillage !== null) {
            $acteurs = array_keys(VracConfiguration::getInstance()->getActeursEmbouteillage());

            if (! in_array($this->document->embouteillage, $acteurs)) {
                parent::addPoint('erreur', 'inexistant', 'L\'acteur n\'existe pas');
            }
        }

        if ($this->document->preparation_vin !== null) {
            $acteurs = array_keys(VracConfiguration::getInstance()->getActeursPreparationVin());

            if (! in_array($this->document->preparation_vin, $acteurs)) {
                parent::addPoint('erreur', 'inexistant', 'L\'acteur n\'existe pas');
            }
        }
    }

    /**
     * Vérifie une date
     *
     * @param string $date Une date
     * @param string $format Le format de date à vérifier
     * @return bool
     */
    private function checkDate($date, $format = 'Y-m-d') {
        if (! $date) {
            return false;
        }

        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Vérifie un numéro
     *
     * @param string $numero Le numéro à vérifier
     * @param int $longueur La longueur du numéro
     * @return bool
     */
    private function checkNumero($numero, $longueur) {
        return preg_match("#\d{$longueur}#", $numero) && sprintf("%{$longueur}d", $numero) === $numero;
    }

    /**
     * Vérifie l'existence d'un etablissement
     *
     * @param string $id L'identifiant de l'établissement
     * @return bool
     */
    public function checkEtablissement($id) {
        if (strlen($id) !== 8) {
            return false;
        }

        $etablissement = $this->etablissement_client->find($id, acCouchdbClient::HYDRATE_JSON);

        return ($etablissement) ? true : false;
    }

    /**
     * Vérifie si le nombre est un float
     *
     * @param string $number Le nombre à vérifier
     * @return bool
     */
    private function checkFloat($number) {
        $number = str_replace(',', '.', $number);

        if (! is_numeric($number) || ! is_float($number)) {
            return false;
        }

        return true;
    }

    /**
     * Vérifie la contenance de la bouteille
     *
     * @param string $contenance La contenance de la bouteille
     * @param string $libelle Libelle de la contenance
     * @return bool
     */
    private function checkContenance($volume, $libelle) {
        $contenances = VracConfiguration::getInstance()->getContenances();

        if (! in_array($volume, $contenances)) {
            return false;
        }

        return array_search($volume, $contenances) === $libelle;
    }
}
