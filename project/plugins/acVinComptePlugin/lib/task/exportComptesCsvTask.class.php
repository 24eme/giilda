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
class exportComptesCsvTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('tiers_types', sfCommandArgument::IS_ARRAY, 'Type du tiers : Recoltant|MetteurEnMarche|Acheteur', array("Recoltant", "MetteurEnMarche", "Acheteur")),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'civa'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'export';
        $this->name = 'comptes-csv';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [exportComptesCsv|INFO] task does things.
Call it with:

  [php symfony export:comptes-csv|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        echo CompteCsvFile::getCsvHeader();

        foreach(CompteAllView::getInstance()->findAllVIEW() as $row) {
            $compte = CompteClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
            if(!isset($compte->societe_informations)) {
                continue;
            }
            echo CompteCsvFile::toCsvLigne($compte);
        }

    }
}