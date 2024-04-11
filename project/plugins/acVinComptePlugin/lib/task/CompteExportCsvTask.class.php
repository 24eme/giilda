<?php

class CompteExportCsvTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(

        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'compte';
        $this->name = 'export-csv';
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $comptes = CompteAllView::getInstance()->findByInterproVIEW('INTERPRO-declaration');

        echo "Date de modifications;Identifiant / Login;Nom / Raison sociale;Adresse;Adresse complémentaire;Code postal;Commune;Code INSEE;Téléphone bureau;Téléphone mobile;Téléphone perso;Fax;Email;Site Internet;SIRET;CVI;Statut;Coopérative;Id du doc\n";
        $i = 0;
        foreach($comptes as $row) {
            $compte = CompteClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
            if((!$compte->mot_de_passe && !$compte->etablissement_informations->cvi) || (!preg_match('/^.SHA./', $compte->mot_de_passe) && strlen($compte->identifiant) > 6)) {
                continue;
            }
            $login = preg_replace("/^([0-9]{6})([0-9]+)$/", '\1', $compte->identifiant);

            if(isset($compte->login) && $compte->login) {
                $login = $compte->login;
            }

            if(!preg_match("/^[0-9]+$/", $login)) {
                continue;
            }

            $societe = SocieteClient::getInstance()->find($compte->id_societe, acCouchdbClient::HYDRATE_JSON);
            $date_modification = $societe->date_modification;

            if(isset($compte->date_modification) && $compte->date_modification) {
                $date_modification = $compte->date_modification;
            }

            $cooperative = "0";
            if($compte->tags && $compte->tags->automatique){
              foreach ($compte->tags->automatique as $t) {
                if($t == "cooperative"){
                    $cooperative = "1";
                }
                if($t == "negociant_vinificateur"){
                    $cooperative = "2";
                }
              }
            }
            echo $date_modification.";".$login.";\"".str_replace('"', '', $compte->nom_a_afficher)."\";\"".str_replace('"', '', $compte->adresse)."\";\"".str_replace('"', '',$compte->adresse_complementaire)."\";".$compte->code_postal.";\"".str_replace('"','',$compte->commune)."\";".$compte->insee.";".$compte->telephone_bureau.";".$compte->telephone_mobile.";".$compte->telephone_perso.";".$compte->fax.";".$compte->email.";\"".str_replace("\n",'',str_replace('"','',$compte->site_internet))."\";".$societe->siret.";".$compte->etablissement_informations->cvi.";".$compte->statut.";".$cooperative.";".$compte->_id."\n";

            $i++;

            if($i > 100) {
                sleep(1);
                $i = 0;
            }
        }

    }


}
