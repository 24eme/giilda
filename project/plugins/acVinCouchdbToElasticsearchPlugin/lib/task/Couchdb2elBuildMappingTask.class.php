<?php

class Couchdb2elBuildMappingTask extends sfBaseTask {


    protected static $defaultProperties = array("seq" => array("type" => "long"),
                                                                "source" => array("type" => "string", "index" => "not_analyzed"),
                                                                "id" => array("type" => "string", "index" => "not_analyzed"),
                                                                "changes" => array("properties" => array("rev" => array("type" => "string", "index" => "not_analyzed"))));

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default')

        ));

        $this->namespace = 'couchdb2el';
        $this->name = 'build-mapping';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
    [build-mapping|INFO] créer un mapping pour l'indexeur elasticsearch
    Appel :
      [php symfony couchdb2el:build-mapping --application="app" |INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
      // initialize the database connection
      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

      if(!$options['application']){
        throw new sfException("Il faut une application pour utiliser cette tache : php symfony couchdb2el:build-mapping --application=\"app\"");
      }

      $mappings = array("mappings" => array());
      $schemas = acCouchdbManager::getInstance()->getSchema();
      foreach ($schemas as $schemaName => $schema) {
        $mappings["mappings"][$schemaName] = array();
        $mappings["mappings"][$schemaName]["properties"] = self::$defaultProperties;
        $mappings["mappings"][$schemaName]["doc"] = array();
        $mappings["mappings"][$schemaName]["doc"]["properties"] = $schema;
      }

      echo json_encode($mappings,JSON_PRETTY_PRINT );
    }

}
