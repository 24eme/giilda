<?php

class Roles
{
    const ADMIN = 'admin';
    const OPERATEUR = 'operateur';
    const TELEDECLARANT = 'teledeclarant';

    const TRANSACTION = 'transactions';
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

    const COMPTA = 'compta';
    const PRESSE = 'presse';
    const DIRECTION = 'direction';
    const BUREAU = 'bureau';
    const AUTRE = 'autre';

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
    );

    public function getRoles($role) {
        $roles = array($role);

        if(isset(self::$hierarchy[$role])) {
            $roles = array_merge($roles, self::$hierarchy[$role]);
        }

        return $roles;
    }
}