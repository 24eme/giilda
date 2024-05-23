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
    $this->addArguments(array(
      new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, 'Document ID'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('verbose', null, sfCommandOption::PARAMETER_REQUIRED, 'Verbose mode', 0),
      new sfCommandOption('dry', null, sfCommandOption::PARAMETER_REQUIRED, 'Dry run', false),
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

    if (!preg_match('/^COMPTE-/',  $arguments['doc_id'])) {
      throw new sfCommandException(sprintf("The Document \"%s\" is not a COMPTE", $arguments['doc_id']));
    }

    $compte = CompteClient::getInstance()->find($arguments['doc_id']);
    if(!$compte) {
      $uid = preg_replace('/COMPTE-/', '', $arguments['doc_id']);
      if (SocieteConfiguration::getInstance()->isIdentifiantEtablissementSaisi() && preg_match('/COMPTE-([0-9]*)(01)?/', $arguments['doc_id'], $match)) {
          $uid = $match[1];
      }
      if ($options['dry']) {
          echo "The LDAP record (uid=".$uid.") will be deleted\n";
          return;
      }
      $ldap = new CompteLdap();
      $ldap->deleteCompte($uid, $options['verbose']);
      return ;
    }
    if ($options['dry']) {
        $ldap = new CompteLdap(true);
        print_r($ldap->info($compte));
        return;
    }
    $compte->updateLdap($options['verbose']);
  }
}
