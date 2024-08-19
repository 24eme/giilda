<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of updateCompteWithDroitsAndTypeSociete
 *
 * @author mathurin
 */
class tagRemoveManuelTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('compteid', null, sfCommandOption::PARAMETER_REQUIRED, 'id du compte', false),
            new sfCommandOption('tag', null, sfCommandOption::PARAMETER_OPTIONAL, 'tag', false),
        ));

        $this->namespace = 'tag';
        $this->name = 'removeManuel';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [removeManuel|INFO] task remove a manual tag in compte.
Call it with:

  [php symfony tag:removeManuel|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection

        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        if ($options['compteid']) {
          $c = CompteClient::getInstance()->find($options['compteid']);
          if(!$c){
              throw new sfException("Le compte ".$options['compteid']."n'existe pas en base");
          }
          $tags_manuel = $c->get('tags')->get('manuel')->toArray(0,1);
          $new_tags_manuel = array();
          foreach ($tags_manuel as $manuel) {
            if(!$options['tag'] || ($manuel != $options['tag'])){
              $new_tags_manuel[] = $manuel;
            }
          }
          $new_tags_manuel = array_unique($new_tags_manuel);
          if(!count($new_tags_manuel)){
            $c->get('tags')->remove('manuel');
          }else{
            $c->get('tags')->remove('manuel');
            $c->get('tags')->add('manuel',$new_tags_manuel);
          }
          $c->save();
        }else{
          throw new sfException("bad arguments");
        }
    }

}
