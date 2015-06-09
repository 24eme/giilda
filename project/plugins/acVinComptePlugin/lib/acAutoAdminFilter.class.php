<?php
/**
 * HTTP Authentication connected to AD filter
 * @author Tangui Morlier <tmorlier@actualys.com>
 * Inspired by James McGlinn <james@mcglinn.org>
 *
 */
class acAutoAdminFilter extends sfFilter
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

    $user->clearCredentials();
    $user->setAuthenticated(true);

    $rights = 'admin';

    $user->setAttribute('AUTH_DESC', $rights);
    $user->addCredential($rights);
    if ($rights == 'admin') {
      $user->addCredential('transactions');
    }
    if ($rights) {
      $user->addCredential('contacts');
    }
    $filterChain->execute();
  }
 

  /**
   * Sends HTTP Auth headers and exits
   *
   */
  public static function logout ($dest = null)
  {
    return ;
  }
}
