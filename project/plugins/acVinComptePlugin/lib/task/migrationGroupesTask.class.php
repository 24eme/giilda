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
 * migrationGroupesTask
 *
 * @package    acVinComptePlugin
 * @subpackage lib/task
 * @author     Mathurin Petit <mpetit@24eme.fr>
 * @version    0.1
 */
class migrationGroupesTask extends sfBaseTask {

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('groupe_origin_name', sfCommandArgument::REQUIRED, "Le nom du groupe d'origine"),
            new sfCommandArgument('groupe_new_name', sfCommandArgument::REQUIRED, "Le nouveau nom du groupe")
        ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'compte';
        $this->name = 'migration-groupes';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $context = sfContext::createInstance($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $groupe_origin_name = $arguments['groupe_origin_name'];
        $groupe_new_name = $arguments['groupe_new_name'];

        $old_name_str_grp = urldecode($groupe_origin_name);
        $old_key_grp = Compte::transformTag($old_name_str_grp);

        $indexELK = acElasticaManager::getType('COMPTE');
        $query = "* doc.tags.groupes:".$old_key_grp;

        $qs = new acElasticaQueryQueryString($query);
        $q = new acElasticaQuery();
        $q->setQuery($qs);
        $q->setLimit(4000);
    		$elasticaFacet = new acElasticaFacetTerms('groupes');
    		$elasticaFacet->setField('doc.tags.groupes');
    		$elasticaFacet->setSize(1000);
   		$q->addFacet($elasticaFacet);
        $resset = $indexELK->search($q);
        $results = $resset->getResults();
        foreach ($results as $key => $value) {
            $data = $value->getData();
            $doc = $data['doc'];
            $compte = CompteClient::getInstance()->findByIdentifiant($doc["identifiant"]);
            $found = false;
            foreach ($compte->groupes as $k => $g) {
                if($g){
                    if($g->nom == $old_name_str_grp){
                        $found = true;
                        $g->nom = $groupe_new_name;
                        break;
                    }
                }
            }
            if(!$found){
                echo $compte->_id." groupe '".$old_name_str_grp."' non trouvé\n";
            }
            $compte->removeTags('groupes',array($old_name_str_grp));
            $compte->addTag('groupes',$groupe_new_name);
            $compte->save();
            echo $compte->_id." : mis à jour du groupe '".$old_name_str_grp."' vers '".$groupe_new_name."'\n";
        }


      }
}
