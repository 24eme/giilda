<?php

class exportSV12Task extends sfBaseTask
{
    protected function configure()
    {
      // // add your own arguments here
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('sv12id', null, sfCommandOption::PARAMETER_OPTIONAL, 'Export a specific SV12', ''),
            new sfCommandOption('entete', null, sfCommandOption::PARAMETER_REQUIRED, "Ligne d'entête", true),
            new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_OPTIONAL, 'Export a specific interpro', ''),
        ));

        $this->namespace        = 'export';
        $this->name             = 'sv12';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony export:sv12|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if(!$options['application']){
          throw new sfException("Le choix de l'application est obligatoire");

        }
        $app = $options['application'];
        if($options["entete"]) {
            echo ExportSV12CSV::getHeaderCsv();
        }
        if ($options['sv12id']) {
            $sv12 = SV12Client::getInstance()->find($options['sv12id']);
            if (!$sv12) {
                return;
            }
            $export = new ExportSV12CSV($sv12, false);
            echo $export->exportSV12($options['interpro']);
            return ;
	    }
        $all_sv12 = SV12AllView::getInstance()->findAll();
        foreach($all_sv12 as $sv12) {
          $sv12 = SV12Client::getInstance()->find($sv12->id);
          if(!$sv12) {
              throw new sfException(sprintf("Document %s introuvable", $sv12->id));
          }
          $export = new ExportSV12CSV($sv12, false);
          echo $export->exportSV12($options['interpro']);
        }
    }
}
