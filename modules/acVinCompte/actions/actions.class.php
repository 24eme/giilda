<?php
require_once dirname(__FILE__).'/../lib/BaseacVinCompteActions.class.php';
/* This file is part of the acVinComptePlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acVinCompte plugin.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class acVinCompteActions extends sfActions 
{   

  public function executeLogin(sfWebRequest $request) {
    $redirect = ($request->getParameter('referer')) ? $request->getParameter('referer') : $request->getUri();

    if($redirect == $this->generateUrl('ac_vin_login', array(), true)) {
      $redirect = null;
    }

    if($redirect == $this->generateUrl('ac_vin_logout', array(), true)) {
      $redirect = null;
    }

    if($redirect == $this->generateUrl('ac_vin_forbidden', array(), true)) {
      $redirect = null;
    }

    if (!$redirect) {
      $redirect = $this->generateUrl('homepage', array(), true);
    }
    return $this->redirect($redirect);
  }

  public function executeForbidden(sfWebRequest $request) {
  }

  public function executeLogout(sfWebRequest $request) {
    $this->setLayout(false);
    if (isset($_SERVER['HTTP_REFERER'])) {
      $referer = $_SERVER['HTTP_REFERER'];
    } else {
      $referer = $this->generateUrl('homepage', array(), true);
    }
    $this->dest = $this->generateUrl('ac_vin_login', array('referer' => $referer), true); //"http://".$_SERVER["SERVER_NAME"];
    if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] != 'logout') {
      $this->dest = preg_replace('/http:\/\//', 'http://logout:logout@', $dest);
    }
  }

}
