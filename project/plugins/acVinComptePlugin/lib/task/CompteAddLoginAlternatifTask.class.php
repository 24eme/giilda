<?php

class CompteAddLoginAlternatifTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('id_compte', sfCommandArgument::REQUIRED, "Id compte"),
            new sfCommandArgument('login', sfCommandArgument::REQUIRED, "Login alternatif"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'compte';
        $this->name = 'add-login-alternatif';
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $compte = CompteClient::getInstance()->find($arguments['id_compte']);

        if(!$compte) {
            return;
        }

        $compte->add('alternative_logins');

        if(in_array($arguments['login'], $compte->alternative_logins->toArray(true, false))) {
            return false;
        }


        $compte->alternative_logins->add(null, $arguments['login']);

        $compte->save();

        echo $compte->_id." login \"".$arguments['login']."\" ajouté\n";
    }


}
