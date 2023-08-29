<?php

class FactureConfiguration {

    private static $_instance = null;
    protected $configuration;
    public $interpro;

    const ALL_KEY = "_ALL";

    public static function getInstance($interpro = null) {
        if (is_null(self::$_instance)) {
            self::$_instance = new FactureConfiguration($interpro);
        }
        $instance = self::$_instance;
        if ($interpro && $instance->interpro != $interpro) {
            self::$_instance = new FactureConfiguration($interpro);
        }
        return self::$_instance;
    }

    public function __construct($interpro = null) {
        $this->interpro = $interpro;
        if(!sfConfig::has("facture_configuration_facture")) {
			throw new sfException("La configuration pour les factures n'a pas été défini pour cette application");
		}
        if ($interpro && !sfConfig::has("facture_configuration_facture-".strtolower($interpro))) {
			throw new sfException("La configuration pour les factures n'a pas été défini pour l'interpro $interpro");
		}
        $this->configuration = array_merge(
            sfConfig::get("facture_configuration_facture", array()),
            sfConfig::get("facture_configuration_facture-".strtolower($interpro), array()),
            sfConfig::get("app_configuration_facture", array()),
            sfConfig::get("app_configuration_facture-".strtolower($interpro), array())
        );
    }

    public function getAll() {

        return $this->configuration;
    }

    public function getPrefixId($facture) {
        if ($facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS)) {

            return $this->configuration['type_libre']['identifiant_prefix'];
        }
        if ($facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DRM)||$facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_SV12)) {

            return $this->configuration['type_cvo']['identifiant_prefix'];
        }

    }

    public function getPaiementsActif() {

        return $this->configuration['paiements_actif'];
    }

    public function getPrefixSage() {

        return $this->configuration['prefix_sage'];
    }

    public function getPrefixSageDivers() {

        return $this->configuration['prefix_sage_divers'];
    }

    public function getPrefixCodeComptable() {

        return isset($this->configuration['prefix_code_comptable']) ? $this->configuration['prefix_code_comptable'] : null;
    }

    public function getTVACompte() {

        return $this->configuration['tva_compte'];
    }

    public function getDefautCompte() {

        return $this->configuration['defaut_compte'];
    }

    public function getStockageCodeProduit() {

        return $this->configuration['stockage_code_produit'];
    }

    public function isPdfProduitFirst() {

        return isset($this->configuration['pdf_produit']) ? $this->configuration['pdf_produit'] : false;
    }

    public function getNomRefClient() {

        return isset($this->configuration['pdf_nom_ref_client']) ? $this->configuration['pdf_nom_ref_client'] : "";
    }

    public function refClientIsCodeComptable() {

        return isset($this->configuration['ref_client_is_code_comptable']) ? $this->configuration['ref_client_is_code_comptable'] : false;
    }

    public function getPdfDiplayCodeComptable() {

        return isset($this->configuration['pdf_display_code_comptable']) ? $this->configuration['pdf_display_code_comptable'] : "";
    }

    public function getNomTaux(){

        return isset($this->configuration['pdf_nom_taux']) ? $this->configuration['pdf_nom_taux'] : "";
    }

    public function getNomInterproFacture(){
      if (!isset($this->configuration['pdf_nom_interpro']))
        return "facture: pdf: nom_interpro A CONFIGURER";
      return $this->configuration['pdf_nom_interpro'];
    }

    public function getPdfLogoInterpro() {
        return (isset($this->configuration['pdf_logo_interpro']))? $this->configuration['pdf_logo_interpro'] : null;
    }

    public function getOrdreCheques(){
        if (!isset($this->configuration['pdf_ordre_cheque']))
          return "facture: pdf: ordre_cheque A CONFIGURER";
        return $this->configuration['pdf_ordre_cheque'];
    }

    public function getEcheance()
  	{
  		return $this->configuration['echeance'];
  	}

    public function getTauxTva() {

        return $this->configuration['taux_tva'];
    }

    public function getExercice() {

        return $this->configuration['exercice'];
    }

    public function getExportShell() {

        return $this->configuration['export_shell'];
    }

    public function getExportRelances() {

        return $this->configuration['export_relances'];
    }

    public function getExportSV12() {

        return $this->configuration['export_sv12'];
    }

    public function getTeledeclaration() {

        return $this->configuration['teledeclaration'];
    }

    public function getIdContrat() {

        return $this->configuration['idcontrat'];
    }

    public function getSeuilMinimum() {

        return $this->configuration['seuil_minimum'];
    }

    public function isPdfLigneDetails() {

        return $this->configuration['pdf_ligne_details'];
    }

    public function getReglement() {
      if (isset($this->configuration['reglement'])) {
        return $this->configuration['reglement'];
      }
      return '\textbf{Dispositions Réglementaires issues de la loi du 10 juillet 1975 : } \\\\ Extrait de l\'article 3 de la loi du 10 juillet 1975 (modifiée par la loi d\'orientation du 4 juillet 1980) \\\\ Les organisations interprofessionnelles reconnues, visées à l\'article 1er, sont habilitées à prélever, sur tous les membres des professions les constituant, des cotisations résultant des accords étendus selon la procédure fixée à l\'article précédent et qui, nonobstant leur caractère obligatoire, demeurent des créances de droit privé. Extrait de l\'article 4 de la loi du 10 juillet 1975 (modifiée par la loi d\'orientation du 4 juillet 1980) \\\\ En cas de violation des règles résultant des accords étendus, il sera alloué par le juge d\'instance, à la demande de l\'organisation interprofessionnelle et à son profit, une indemnité dont les limites sont comprises entre 76 euros et la réparation intégrale du préjudice subi. \\\\ Extrait de l\'article 4 bis de la loi du 10 juillet 1975 (modifiée par la loi d\'orientation du 4 juillet 1980) \\\\ Lorsque, à l\'expiration d\'un délai de trois mois suivant leur date d\'exigibilité, les cotisations prévues à l\'article 3 ci-dessus ou une indemnité allouée en application de l\'article 4 ci-dessus n\'ont pas été acquittées, l\'organisation interprofessionnelle peut, après avoir mis en demeure le redevable de régulariser sa situation, utiliser la procédure d\'opposition prévue à l\'alinéa 3° de l\'article 1143-2 du code rural.\\\\';
    }

    public function getReglementAvoir() {
      if (isset($this->configuration['reglement_avoir'])) {
        return $this->configuration['reglement_avoir'];
      }
      return '';
    }

    public function getCodeJournal() {

        return isset($this->configuration['code_journal']) ? $this->configuration['code_journal'] : null;
    }

    public function getBanqueCompte() {

        return isset($this->configuration['banque_compte']) ? $this->configuration['banque_compte'] : null;
    }

    public function getGeneralCompte() {

        return isset($this->configuration['general_compte']) ? $this->configuration['general_compte'] : null;
    }

    public function getDelaiRelance1() {
        return isset($this->configuration['delai_relance1']) ? $this->configuration['delai_relance1'] : null;
    }

    public function getDelaiRelance2() {
        return isset($this->configuration['delai_relance2']) ? $this->configuration['delai_relance2'] : null;
    }

    public function getRelances() {
        $delais = array();
        if ($delai = $this->getDelaiRelance1()) {
            $delais[1] = $delai;
        }
        if ($delai = $this->getDelaiRelance2()) {
            $delais[2] = $delai;
        }
        return $delais;
    }

    public static function isMultiInterproFacturables() {
        $conf = sfConfig::get("facture_configuration_facture", []);
        return isset($conf["multi_interpro_facturables"])? $conf["multi_interpro_facturables"] : false;
    }

    public function getEcheanceFinDeMois() {
  		return isset($this->configuration['echeance_fin_de_mois']) ? $this->configuration['echeance_fin_de_mois'] : false;
  	}

    public function getEmetteurCvo() {
        return isset($this->configuration['emetteur_cvo']) ? $this->configuration['emetteur_cvo'] : array();
    }

    public function getEmetteurLibre() {
        return isset($this->configuration['emetteur_libre']) ? $this->configuration['emetteur_libre'] : array();
    }

    public function getCoordonneesBancaire() {
        return isset($this->configuration['coordonnees_bancaire']) ? $this->configuration['coordonnees_bancaire'] : array();
    }

    public function getInfosInterpro() {
        return isset($this->configuration['infos_interpro']) ? $this->configuration['infos_interpro'] : array();
    }

    public function getGlobaliseCalculTaxe() {
  		return isset($this->configuration['globalise_calcul_taxe']) ? $this->configuration['globalise_calcul_taxe'] : false;
  	}

    public function getNomInterproTeledeclaration() {
  		return isset($this->configuration['nom_interpro_teledeclaration']) ? $this->configuration['nom_interpro_teledeclaration'] : '';
  	}

    public function getEmailInterproTeledeclaration() {
  		return isset($this->configuration['email_interpro_teledeclaration']) ? $this->configuration['email_interpro_teledeclaration'] : '';
  	}

    public function getFacturationMetasActif() {
  		return isset($this->configuration['facturation_metas_actif']) ? $this->configuration['facturation_metas_actif'] : false;
  	}

}
