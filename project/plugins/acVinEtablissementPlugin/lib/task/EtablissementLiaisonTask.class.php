<?php

class EtablissementLiaisonTask extends sfBaseTask
{
    protected function configure()
    {
        // add your own arguments here
        $this->addArguments(array(
          new sfCommandArgument('metayer', sfCommandArgument::REQUIRED, "ID, Identifiant, CVI du métayer"),
          new sfCommandArgument('bailleur', sfCommandArgument::REQUIRED, "ID, Identifiant, PPM du bailleur"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            // add your own options here
        ));

        $this->namespace        = 'etablissement';
        $this->name             = 'liaison';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [etalissement:liaison|INFO] task does things.
Call it with:

  [php symfony etalissement:liaison|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $metayer = EtablissementClient::getInstance()->findBy($arguments['metayer']);
        $bailleur = EtablissementClient::getInstance()->findBy($arguments['bailleur']);

        if(!$metayer) {
            throw new sfException("Metayer non trouvé : ".$arguments['metayer']);
        }

        if(!$bailleur) {
            throw new sfException("Bailleur non trouvé : ".$arguments['bailleur']);
        }

        $metayer->addLiaison(EtablissementClient::TYPE_LIAISON_BAILLEUR, $bailleur);
        $metayer->save();
        $bailleur->addLiaison(EtablissementClient::TYPE_LIAISON_METAYER, $metayer);
        $bailleur->save();


    }
}
