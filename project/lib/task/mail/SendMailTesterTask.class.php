<?php

class SendMailTesterTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments([
            new sfCommandArgument('adresse_email', sfCommandArgument::REQUIRED, "Adresse email d'expedition")
        ]);

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = "mail";
        $this->name = "mail-tester";
        $this->briefDescription = 'Envoi un mail de test';
        $this->detailedDescription = <<<EOF
EOF;
    }

    public function execute($arguments = [], $options = [])
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        sfContext::createInstance($this->configuration);

        $from = [ sfConfig::get('app_email_plugin_from_adresse') => sfConfig::get('app_email_plugin_from_name') ];
        $to = [$arguments['adresse_email']];
        $subject = "Validation de la configuration des mails";
        $body = "Bonjour, voici un simple mail pour tester la configuration des mails sur l'application";

        $swiftmessage = sfContext::getInstance()->getMailer()->compose();
        $message = $swiftmessage
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/plain');

        sfContext::getInstance()->getMailer()->send($message);
    }
}
