<?php

class DRMMailNotificationReserveInterproTask extends sfBaseTask
{
    private $hashes = [
       "RI" => "certifications/AOC_ALSACE/genres/TRANQ/appellations/ALSACEBLANC/mentions/DEFAUT/lieux/DEFAUT/couleurs/DEFAUT/cepages/RI",
       "PG" => "certifications/AOC_ALSACE/genres/TRANQ/appellations/ALSACEBLANC/mentions/DEFAUT/lieux/DEFAUT/couleurs/DEFAUT/cepages/PG",
       "GW" => "certifications/AOC_ALSACE/genres/TRANQ/appellations/ALSACEBLANC/mentions/DEFAUT/lieux/DEFAUT/couleurs/DEFAUT/cepages/GW",
    ];

    private $mailPath = __DIR__.'/../../../../bin/reserve/mail.txt';

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('cvi', sfCommandArgument::REQUIRED, "cvi"),
            new sfCommandArgument('campagne', sfCommandArgument::REQUIRED, "campagne"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'drm';
        $this->name             = 'mail-notification-reserveinterpro';
        $this->briefDescription = 'Envoi le mail de réserve aux opérateurs';
        $this->detailedDescription = "Tâche d'envoi de mail à lancer une fois que la mise à jour des DRM est faite";

    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $context = sfContext::createInstance($this->configuration);

        $etablissement = EtablissementClient::getInstance()->findByCvi($arguments['cvi']);

        $historique = new DRMHistorique($etablissement->identifiant, $arguments['campagne']);
        $drm = $historique->getLast();

        if (!$drm) {
            echo $arguments['cvi']." no drm\n";
            return;
        }

        $PG = $volPG = 0;
        $RI = $volRI = 0;
        $GW = $volGW = 0;

        foreach ($this->hashes as $cepage => $hash) {
            if ($drm->declaration->exist($hash) == false) {
                $$cepage = false;

                continue;
            }

            $$cepage = true;

            $produit = $drm->declaration->get($hash);

            if ($produit->exist('reserve_interpro') && $produit->reserve_interpro > 0) {
                ${'vol'.$cepage} = $produit->reserve_interpro;
            }
        }

        foreach ($data = file($this->mailPath) as $line => $text) {
            if ($PG === false && strpos($text, '• Pinot Gris') !== false) {
                unset($data[$line]);
            }

            if ($RI === false && strpos($text, '• Riesling') !== false) {
                unset($data[$line]);
            }

            if ($GW === false && strpos($text, '• Gewurztraminer') !== false) {
                unset($data[$line]);
            }

            if ($PG === true && strpos($text, '%pg_vol%') !== false) {
                $data[$line] = str_replace('%pg_vol%', $volPG, $data[$line]);
            }

            if ($RI === true && strpos($text, '%ri_vol%') !== false) {
                $data[$line] = str_replace('%ri_vol%', $volRI, $data[$line]);
            }

            if ($GW === true && strpos($text, '%gw_vol%') !== false) {
                $data[$line] = str_replace('%gw_vol%', $volGW, $data[$line]);
            }
        }

        $mailFinal = implode("", $data);

        $email = $etablissement->getTeledeclarationEmail();

        $fromEmail = sfConfig::get('app_mail_from_email');
        $fromName  = sfConfig::get('app_mail_from_name');

        $message = Swift_Message::newInstance()
         ->setFrom($fromEmail, $fromName)
         ->setTo($email)
         ->setSubject("Réserve Interprofessionnelle")
         ->setBody($mailFinal);

        $sent = $this->getMailer()->send($message);
        return $sent;
    }
}
