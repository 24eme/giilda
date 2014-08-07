<?php

require_once(dirname(__FILE__).'/vendor/phpCAS/CAS.php');

class acCAS extends phpCAS {

    public static function processAuth() {
        @acCAS::client(CAS_VERSION_2_0, sfConfig::get('app_cas_domain'), sfConfig::get('app_cas_port'), sfConfig::get('app_cas_path'), false);
        @acCAS::setNoCasServerValidation();
        @acCAS::forceAuthentication();
    }

    public static function processLogout($url) {
        @phpCAS::client(CAS_VERSION_2_0,sfConfig::get('app_cas_domain'), sfConfig::get('app_cas_port'), sfConfig::get('app_cas_path'), false);
        if (@phpCas::isAuthenticated()) {
            @phpCAS::logoutWithRedirectService($url);
        }
    }
}