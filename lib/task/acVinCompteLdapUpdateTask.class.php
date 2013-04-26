<?php

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
 * acVinComptePlugin task.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class acVinCompteLdapUpdateTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('compte', null, sfCommandOption::PARAMETER_REQUIRED, 'Compte id to update to ldap', 0),
      new sfCommandOption('verbose', null, sfCommandOption::PARAMETER_REQUIRED, 'Verbose mode', 0),
    ));

    $this->namespace        = 'compte';
    $this->name             = 'ldap-update';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tiers:ldap-update|INFO] task does things.
Call it with:

  [php symfony tiers:ldap-update|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    ini_set('memory_limit', '512M');
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    if ($options['compte']) {
       $c = new stdClass();
       $c->key = array(CompteAllView::KEY_ID => 'COMPTE-'.$options['compte']);
       $compteview = array($c);
    }else{
     $compteview = CompteAllView::getInstance()->findByInterpro('INTERPRO-inter-loire', '', 100000);
    }

    $nb = 0;
    foreach($compteview as $compteinfo) {
      if ($options['verbose']) {
       echo "vue : ";
       print_r($compteinfo);
       echo "\n";
      }
      $compte = acCouchdbManager::getClient('Compte')->retrieveDocumentById($compteinfo->key[CompteAllView::KEY_ID]);
      if (!$compte) {
	continue;
      }
      if ($options['verbose']) {
	echo "compte : ";
	print_r($compte);
       echo "\n";
      }
      $this->log($compte->identifiant);
      $nb++;
      $compte->updateLdap($options['verbose']);
    }

    $this->logSection("done", $nb);
  }
}
