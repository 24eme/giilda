<?php

class defactureFactureTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        $this->addArgument('factureid', sfCommandArgument::REQUIRED, 'L\'id de la facture');
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'vinsdeloire'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('avoir', null, sfCommandOption::PARAMETER_OPTIONAL, 'Genere un avoir', true)
            // add your own options here
        ));

    $this->namespace        = 'facture';
    $this->name             = 'defacturer';
    $this->briefDescription = 'Defacture et génère (optionnellement) un avoir (oui par défaut)';
    $this->detailedDescription = <<<EOF
The [defactureFacture|INFO] task does things.
Call it with:

    [php symfony facture:defacturer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    if(! $arguments['factureid']) {
        throw new sfException('factureid neeeded');
    }

    echo $arguments['factureid'] . " : ";
    $facture = FactureClient::getInstance()->find($options['factureid']);

    if (! $facture) {
        echo 'Facture non trouvé'.PHP_EOL;
    }

    if ($options['avoir']) {
        $resultat = FactureClient::getInstance()->defactureCreateAvoirAndSaveThem($facture);
        echo ($resultat)
            ? $avoir->_id
            : 'ERROR: '.$facture->_id.' not generated';
        echo PHP_EOL;
    } else {
        $facture->defacturer();
        $facture->save();
        echo $facture->_id .PHP_EOL;
    }
  }
}
