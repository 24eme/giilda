<?php

class Roles
{

    const CONTRAT = 'contrat';
    const DRM = 'drm';
    const FACTURE = 'facture';
    const DREV = 'drev';
    const SV12 = 'sv12';
    const DS = 'ds';
    const STOCK = 'stock';
    const ALERTE = 'alerte';
    const RELANCE = 'relance';
    const CONTACT = 'contacts';
    const STATS = 'stats';

    const ADMIN = 'admin';
    const OPERATEUR = 'operateur';
    const TRANSACTION = 'transactions';
    const COMPTA = 'compta';
    const PRESSE = 'presse';
    const DIRECTION = 'direction';
    const BUREAU = 'bureau';
    const AUTRE = 'autre';

    const ROLEDRM = 'DRM';

    const TELEDECLARATION = 'teledeclaration';
    const TELEDECLARATION_VRAC = 'teledeclaration_vrac';
    const TELEDECLARATION_VRAC_CREATION = 'teledeclaration_vrac_creation';
    const TELEDECLARATION_DRM = 'teledeclaration_drm';
    const TELEDECLARATION_DRM_ACQUITTE = 'teledeclaration_drm_acquitte';
    const TELEDECLARATION_FACTURE = 'teledeclaration_facture';
    const TELEDECLARATION_FACTURE_EMAIL = 'teledeclaration_facture_email';
    const TELEDECLARATION_DREV = 'teledeclaration_drev';
    const TELEDECLARATION_DREV_ADMIN = 'teledeclaration_drev_admin';
    const TELEDECLARATION_DOUANE = 'teledeclaration_douane';
    const TELEDECLARATION_PRELEVEMENT = 'teledeclaration_prelevement';

    const OBSERVATOIRE = 'observatoire';

    public static $teledeclarationLibelles = array(
      self::TELEDECLARATION => "Teledeclaration",
      self::TELEDECLARATION_VRAC => "Teledeclaration signature contrats",
      self::TELEDECLARATION_VRAC_CREATION => "Teledeclaration création contrats",
      self::TELEDECLARATION_DRM => "Teledeclaration DRM",
      self::TELEDECLARATION_DOUANE => "Transmission Ciel",
      self::TELEDECLARATION_DRM_ACQUITTE => "Teledeclaration DRM acquittée",
      self::TELEDECLARATION_FACTURE => "Factures",
      self::TELEDECLARATION_FACTURE_EMAIL => "Factures par email",
      self::TELEDECLARATION_DREV => "Drev",
      self::TELEDECLARATION_DREV_ADMIN => "Drev Administration",
      self::TELEDECLARATION_PRELEVEMENT => "Prélèvement automatique"
      );

      public static $teledeclarationLibellesShort = array(
        self::TELEDECLARATION => "Accès à la télédeclaration",
        self::OBSERVATOIRE => "Accès à l'observatoire économique",
        self::TELEDECLARATION_VRAC => "Signature contrats",
        self::TELEDECLARATION_VRAC_CREATION => "Création contrats",
        self::TELEDECLARATION_DRM => "DRM",
        self::TELEDECLARATION_DRM_ACQUITTE => "DRM acquittée",
        self::TELEDECLARATION_DOUANE => "Transmission Ciel",
        self::TELEDECLARATION_FACTURE => "Factures",
        self::TELEDECLARATION_FACTURE_EMAIL => "Factures par email",
        self::TELEDECLARATION_DREV => "Drev",
        self::TELEDECLARATION_DREV_ADMIN => "Drev Administration",
        self::TELEDECLARATION_PRELEVEMENT => "Prélèvement automatique",
        self::DRM => "DRM",
        self::CONTRAT => "Contrat",
        );

    protected static $hierarchy = array(
        self::ADMIN => array(self::OPERATEUR, self::TRANSACTION),
        self::OPERATEUR => array(),
        self::TRANSACTION => array(self::CONTRAT,
                                   self::DRM,
                                   self::FACTURE,
                                   self::DREV,
                                   self::SV12,
                                   self::DS,
                                   self::STOCK,
                                   self::ALERTE,
                                   self::RELANCE,
                                   self::CONTACT),
        self::COMPTA => array(self::CONTACT),
        self::PRESSE => array(self::CONTACT),
        self::DIRECTION => array(self::CONTACT),
        self::BUREAU => array(self::CONTACT),
        self::AUTRE => array(self::CONTACT),
        self::ROLEDRM => array(self::DRM),
    );

    public static function getRoles($role) {
        $roles = array($role);

        if(isset(self::$hierarchy[$role])) {
            foreach(self::$hierarchy[$role] as $r) {
                $roles = array_merge($roles, self::getRoles($r));
            }

        }

        return $roles;
    }

    public function getRolesCompte() {
        return array(self::CONTRAT => "Contrat");
    }
}
