<?php

class DRMConfiguration {

    private static $_instance = null;
    protected $configuration;

    const ALL_KEY = "_ALL";

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new DRMConfiguration();
        }
        return self::$_instance;
    }

    public function __construct() {
        if(!sfConfig::has('drm_configuration_drm')) {
			throw new sfException("La configuration pour les drm n'a pas été défini pour cette application");
		}

        $this->configuration = sfConfig::get('drm_configuration_drm', array());
    }

    public function getAll() {

        return $this->configuration;
    }

    public function getExportDetail() {

        return $this->configuration['details']['export_detail'];
    }

    public function isVracCreation() {

        return boolval($this->configuration['details']['vrac_detail']['creation']);
    }

    public function getFamilles() {

        return $this->configuration['familles'];
    }

    public function getExportPaysDebut() {

        return $this->configuration['export_pays_debut'];
    }

    public function getExportPaysFin() {

        return $this->configuration['export_pays_fin'];
    }

    public function getRepriseDonneesUrl() {

        $url_reprise_donnees_drm = sfConfig::get('app_url_reprise_donnees_drm');
        if ($url_reprise_donnees_drm) {
            return $url_reprise_donnees_drm;
        }
        return $this->configuration['reprise_donnees_url'];
    }

    public function getFinalRepriseDonneesUrl($identifiant, $periode, $options = null) {
        if (!$options) {
            $options = [];
        }

        $url_reprise_donnees_drm = $this->getRepriseDonneesUrl();
        $url_reprise_donnees_drm = str_replace(":identifiant", $identifiant, $url_reprise_donnees_drm);
        $url_reprise_donnees_drm = str_replace(":periode", $periode, $url_reprise_donnees_drm);

        if(isset($options['aggregate']) && $options['aggregate']) {
            $url_reprise_donnees_drm.= '?aggregate='.$options['aggregate'];
        }

        if(isset($options['lieudit']) && $options['lieudit']) {
            $url_reprise_donnees_drm.= '?lieudit='.$options['lieudit'];
        }

        if(isset($options['firstdrm']) && $options['firstdrm']) {
            $url_reprise_donnees_drm.= "?firstdrm=".$options['firstdrm'];
        }

        return $url_reprise_donnees_drm;
    }

    public function hasSansContratOption() {

        return $this->configuration['sans_contrat_option'];
    }

    public function getDelaiOuvertureTeledeclaration() {

        return $this->configuration['delai_ouverture_teledeclaration'];
    }

    public function isDRMVracMultiProduit() {

        return $this->configuration['vrac_multi_produit'];
    }

    public function hasEdiDefaultProduitHash() {
        if (!isset($this->configuration['edi_default_produit_hash'])) {
            return false;
        }
        if (!is_array($this->configuration['edi_default_produit_hash'])) {
            return false;
        }
        return true;

    }

    public function getEdiDefaultProduitHash($inao) {
        if (!$this->hasEdiDefaultProduitHash()) {
            return "";
        }
        $hashes = $this->configuration['edi_default_produit_hash'];
        if (preg_match('/^.....M/', $inao) || preg_match('/^VM_/', $inao)) {
            return $hashes['MOU'];
        }
        if (!preg_match('/^VT_/', $inao) && preg_match('/_/', $inao)) {
            return $hashes['AUTRE'];
        }
        return $hashes['TRANQ'];
    }

    public function isCrdOnlySuspendus() {

        return $this->configuration['crd_only_suspendus'];
    }

    public function hasAggregatedEdi() {

        return boolval($this->configuration['aggregate_edi']);
    }

    public function getAggregatedEdi() {

        return $this->configuration['aggregate_edi'];
    }

    public function hasDeclassementIgp() {

        return $this->configuration['declassement_igp'];
    }

    public function isRepriseStocksChangementCampagne() {

        return $this->configuration['reprise_stocks_changement_campagne'];
    }

    public function isPdfCvo() {

        return $this->configuration['pdf_cvo'];
    }

    public function isMouvementDivisable() {

        return $this->getMouvementDivisableSeuil() !== false;
    }

    public function getMouvementDivisableSeuil() {

        return $this->configuration['mouvement_divisable_seuil'];
    }

    public function getMouvementDivisableNbMonth() {

        return $this->configuration['mouvement_divisable_nb_month'];
    }

    public function getDefaultCrds() {

        return $this->configuration['defaults_crds_nodes'];
    }

    public function isProduitAutoChecked(){
        return boolval($this->configuration['default_auto_checked']);
    }

    public function getPdfFontSize(){
        return $this->configuration['pdf_font_size'];
    }

    public function getXmlTransfertEchec($cielResponse){
        $erreur = preg_replace('/<\/erreur-fonctionnelle>/' ,'<br/></erreur-fonctionnelle>', html_entity_decode($cielResponse));
        if (preg_match('/HTTP Error \d/', $cielResponse) || preg_match('/permission to access .authtoken.oauth2/', $cielResponse) || preg_match('/invalid certificate/', $cielResponse) || preg_match('/Access token/', $cielResponse)) {
            $erreur = "<strong>Le service de reception des DRM de la Douane est indisponible pour le moment</strong>";
        }
        return "<p>".str_replace("DESCRIPTION_ERREUR",$erreur,nl2br($this->configuration['xml_transfert_echec']))."</p>";
    }

    public function isCampagneListeMinimale(){
        return $this->configuration['campagne_liste_minimale'];
    }

    public function getNbExtraPDFPages() {
      return $this->configuration['nb_extra_pdf_pages'];
    }

    public function isObservationsAuto() {
        return $this->configuration['observations_auto'];
    }

    public function hasWarningForProduit() {
        return $this->configuration['warning_produit'];
    }

    public function getWarningsMessagesForProduits($produits) {
      $messages = array();
      if(!$this->hasWarningForProduit()){
        return $messages;
      }
      foreach ($this->hasWarningForProduit() as $key => $warnings) {
        foreach ($produits as $hash => $p) {
          if(preg_match($warnings["regex"],$hash)){
            $messages[] = $warnings["message"];
          }
        }
      }
      return array_unique($messages);
    }

    public function isMouvementVideNeant() {

        return $this->configuration['mouvement_vide_neant'];
    }

    public function isNegociantFacturable() {

        return $this->configuration['negociant_facturable'];
    }

    public function hasMatierePremiere() {

        return $this->getConfig('matiere_premiere');
    }

    public function getCRDGenres() {
        $genres = array();
        foreach($this->configuration['crds_genre'] as $genre) {
            $genres[$genre] = DRMClient::$drm_crds_genre[$genre];
        }

        return $genres;
    }

    public function getConfig($name) {
        if(!isset($this->configuration[$name])) {

            return null;
        }

        return $this->configuration[$name];
    }

    public function hasActiveReserveInterpro() {
        $desactive = $this->getConfig('desactive_reserve_interpro');
        if ($desactive === true) {
            return false;
        }
        return ($this->getConfig('reserve_interpro_message'));
    }

    public function getRerserveInteproMessage() {
        if (!$this->hasActiveReserveInterpro()) {
            return null;
        }
        return $this->getConfig('reserve_interpro_message');
    }

}
