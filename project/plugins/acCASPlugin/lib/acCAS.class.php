<?php

require_once(dirname(__FILE__).'/vendor/phpCAS/CAS.php');

class acCAS extends phpCAS {

    public static function getConfig($str) {
      if (isset($_SESSION[$str])) {
        return ($_SESSION[$str]);
      }
      return sfConfig::get($str);
    }

    private static function initCasInfo() {
      $postfix = '';
      if (isset($_GET['cas_postfix'])) {
        $postfix = $_GET['cas_postfix'];
      }
      if (isset($_GET['ticket'])) {
        $postfix = preg_replace('/.*-/', '', $_GET['ticket']);
      }
      if (!$postfix && isset($_SESSION['app_cas_origin'])) {
          $postfix = $_SESSION['app_cas_origin'];
      }
      $multidomains = sfConfig::get('app_cas_multidomains', array());
      if ($postfix == 'viticonnect' && !isset($multidomains[$postfix])) {
          $multidomains['viticonnect'] = array( 'domain' => 'viticonnect.net', 'port' => '443', 'path' => 'cas', 'url' => 'https://viticonnect.net/cas' );
      }
      if ($postfix && count($multidomains) && isset($multidomains[$postfix])) {
        $_SESSION['app_cas_domain'] = $multidomains[$postfix]['domain'];
        $_SESSION['app_cas_port'] = $multidomains[$postfix]['port'];
        $_SESSION['app_cas_path'] = $multidomains[$postfix]['path'];
        $_SESSION['app_cas_url'] = $multidomains[$postfix]['url'];
        $_SESSION['app_cas_origin'] = $postfix;
      }else{
        $_SESSION['app_cas_domain'] = sfConfig::get('app_cas_domain');
        $_SESSION['app_cas_port'] = sfConfig::get('app_cas_port');
        $_SESSION['app_cas_path'] = sfConfig::get('app_cas_path');
        $_SESSION['app_cas_url'] = sfConfig::get('app_cas_url');
      }
    }

    public static function processAuth() {
        self::initCasInfo();
        //phpCAS::setDebug('/tmp/cas.log');
        @acCAS::client(CAS_VERSION_2_0, $_SESSION['app_cas_domain'], $_SESSION['app_cas_port'], $_SESSION['app_cas_path'], false);
        @acCAS::setNoCasServerValidation();
        @acCAS::forceAuthentication();
    }

    public static function processLogout($url) {
        self::initCasInfo();
        @phpCAS::client(CAS_VERSION_2_0, $_SESSION['app_cas_domain'], $_SESSION['app_cas_port'], $_SESSION['app_cas_path'], false);
        if (@phpCas::isAuthenticated()) {
            @phpCAS::logoutWithRedirectService($url);
        }
    }
}
