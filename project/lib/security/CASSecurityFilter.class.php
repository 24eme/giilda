<?php
/**
 * HTTP Authentication connected to AD filter
 * @author Tangui Morlier <tmorlier@actualys.com>
 * Inspired by James McGlinn <james@mcglinn.org>
 *
 */
class CASSecurityFilter extends sfBasicSecurityFilter
{

  protected $request = null;
  protected $routing = null;
  protected $controller = null;

  public function initialize($context, $parameters = array())
  {
    parent::initialize($context, $parameters);

    $this->request = $context->getRequest();
    $this->routing = $context->getRouting();
    $this->controller = $context->getController();
  }
  /**
   * Execute filter
   *
   * @param sfFilterChain $filterChain
   */
  public function execute ($filterChain)
  {
      if ($this->request->getParameter('ticket')) {
          /** CAS * */
          error_reporting(E_ALL);
          require_once(sfConfig::get('sf_lib_dir') . '/vendor/phpCAS/CAS.class.php');
          phpCAS::client(CAS_VERSION_2_0, sfConfig::get('app_cas_domain'), sfConfig::get('app_cas_port'), sfConfig::get('app_cas_path'), false);
          phpCAS::setNoCasServerValidation();
          $this->getContext()->getLogger()->debug('{sfCASRequiredFilter} about to force auth');
          phpCAS::forceAuthentication();
          $this->getContext()->getLogger()->debug('{sfCASRequiredFilter} auth is good');
          /** ***** */
          $this->getUser()->signIn(phpCAS::getUser());
      }

      parent::execute($filterChain);
  }

  protected function forwardToLoginAction()
  {
      $this->controller->redirect(sfConfig::get('app_cas_url') . '/login?service=' . $this->request->getUri());

      throw new sfStopException();
  }
 
}
