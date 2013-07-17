<?php
/**
 * HTTP Authentication connected to AD filter
 * @author Tangui Morlier <tmorlier@actualys.com>
 * Inspired by James McGlinn <james@mcglinn.org>
 *
 */
class acHttpAuth2ADFilter extends sfFilter
{
  /**
   * Execute filter
   *
   * @param sfFilterChain $filterChain
   */
  public function execute ($filterChain)
  {
    $context = $this->getContext(); 	
    $user = $context->getUser();
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
      acHttpAuth2ADFilter::logout();
    }

    if ($user->getAttribute('AUTH_USER') == $_SERVER['PHP_AUTH_USER']) {
      return $filterChain->execute();
    }
    $user->setAttribute('AUTH_USER', $_SERVER['PHP_AUTH_USER']);
    $filterChain->execute();
  }
 

  /**
   * Sends HTTP Auth headers and exits
   *
   */
  public static function logout ()
  {
    header('WWW-Authenticate: Basic realm="' . sfConfig::get('app_auth_realm') . '"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
  }
}