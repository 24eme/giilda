<?php

class exportChaisCsvTask extends sfBaseTask
{

    protected $chais = array();
    protected $activitesCorespondance = array();

    public static $activiteOrdre = array("Apport" => "0","Vinification" => "1","DGC" => "2","VV Stockage" => "3","VC Stockage" => "4");

    protected function configure()
    {
        $this->addArguments(array(
        ));

        $this->addOptions(array(
            new sfCommandOption('without-liaisons', null, sfCommandOption::PARAMETER_REQUIRED, 'Sans liaisons'),
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'export';
        $this->name = 'chais-csv';
        $this->briefDescription = "Export csv des établissements";
        $this->detailedDescription = <<<EOF
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $results = EtablissementClient::getInstance()->findAll();

        $withoutLiaisons = (isset($options['without-liaisons']) && $options['without-liaisons']);
        echo "Identifiant chais,Identifiant,Type,Chais Activites,Raison sociale,Nom,Adresse 1,Adresse 2,Adresse 3,Code postal,Commune,Nom Contact,Tèl Contact, Carte,Position,Archivé,IdCIVP,EA1,EA2,SIRET,Organisme,DocId\n";
        $this->activitesCorespondance = array_flip(EtablissementClient::$chaisAttributsInImport);
        if(!$withoutLiaisons){
           $cpt = 0;
            foreach($results->rows as $row) {
               $cpt++;
               if($cpt > 500) {
                       sleep(3);
                       $cpt = 0;
               }
                $etablissement = EtablissementClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
                if(isset($etablissement->chais)){
                    foreach($etablissement->chais as $numChai => $chai) {
                        $chai->etablissement_nom = $etablissement->nom;
                        $this->chais[$etablissement->_id.'/chais/'.$numChai] = $chai;
                    }
                }
            }
        }

        $cpt = 0;
        foreach($results->rows as $row) {
            $cpt++;
            if($cpt > 500) {
                sleep(3);
                $cpt = 0;
            }
            $etablissement = EtablissementClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
            if(isset($etablissement->chais)){
                foreach($etablissement->chais as $numChai => $chai) {
                    $activites = array();
                    foreach($chai->attributs as $aKey => $a) {
                        $activites[] = $aKey;
                    }
                    sort($activites);
                    $activites = implode(";", $this->transformActivites($activites));
                    $isArchivee = $this->isArchiveeChai($chai);
                    $adresses = explode(' - ', str_replace(array('"',','),array('',''),$chai->adresse));
                    $a_comp = (isset($adresses[1]))? $adresses[1] : "";
                    $a_comp1 = (isset($adresses[2]))? $adresses[2] : "";

                    $numChaiStr = $numChai+1;
                    echo preg_replace('/ETABLISSEMENT-CDP([0-9]+)01$/',"CDP$1",$etablissement->_id)."/".$numChaiStr.",".
                    preg_replace('/ETABLISSEMENT-CDP([0-9]+)01$/',"CDP$1",$etablissement->_id).",".
                    "Autre,".
                    $activites.",".
                    $etablissement->nom.",".
                    $chai->nom.",".
                    trim(str_replace('"', '', $adresses[0])).",".
                    trim(str_replace('"', '', $a_comp)).",".
                    trim(str_replace('"', '', $a_comp1)).",".
                    $chai->code_postal.",".
                    $this->protectIso($chai->commune).",".
                    $this->transformNom($this->protectIso($etablissement->raison_sociale)).",".
                    $etablissement->telephone_bureau.",".
                    ",,".$isArchivee.",,,,".$etablissement->siret.','.
                    Organisme::getCurrentOrganisme().",".
                    $etablissement->_id.
                    "\n";
                    }
                }
                if(!$withoutLiaisons && isset($etablissement->liaisons_operateurs)){
                    foreach($etablissement->liaisons_operateurs as $numLiaison => $liaison) {
                        if(!isset($liaison->hash_chai) || !$liaison->hash_chai){
                            continue;
                        }
                        $keyL = $liaison->id_etablissement.$liaison->hash_chai;
                        if(!array_key_exists($keyL,$this->chais)){
                            throw new sfException("Le chai $keyL n'a pas été réferencé");
                        }

                        $chaiDistant = $this->chais[$keyL];

                        $activites = array();

                        $attributs = array();
                        foreach($liaison->attributs_chai as $attribut) {
                            $attributs[] = $attribut;
                            $activites[] = $attribut;
                        }
                        sort($activites);
                        $activites = implode(";", $this->transformActivites($activites));

                        $adresses = explode(' − ', str_replace(array('"',','),array('',''),$chai->adresse));
                        $a_comp = (isset($adresses[1]))? $adresses[1] : "";
                        $a_comp1 = (isset($adresses[2]))? $adresses[2] : "";

                        sort($attributs);
                        $attributs = implode(";", $attributs);
                        $type_chai = "Autre";
                        if($attributs == EtablissementClient::CHAI_ATTRIBUT_APPORT){
                            $type_chai = "Apporteur";
                            $activites = "Apport";
                        }

                        $adresses = explode(' - ', str_replace(array('"',','),array('',''),$chaiDistant->adresse));
                        $a_comp = (isset($adresses[1]))? $adresses[1] : "";
                        $a_comp1 = (isset($adresses[2]))? $adresses[2] : "";

                        $identifiantsChais = array();

                        if(!preg_match('/ETABLISSEMENT-CDP([0-9]+)01\/chais\/([0-9]+)$/',$keyL, $identifiantsChais)){
                            throw new sfException("l'identifiant du chai n'est pas bon pour $keyL");
                        }
                        if(count($identifiantsChais) < 2){
                            throw new sfException("l'identifiant du chai n'est pas bon pour $keyL");
                        }

                        $telephone = ($etablissement->telephone_mobile)? $etablissement->telephone_mobile : $etablissement->telephone_bureau;
                        $isArchivee = $this->isArchiveeChai($chaiDistant);
                        $numChaiStr = $identifiantsChais[2]+1;
                        echo "CDP".$identifiantsChais[1]."/".$numChaiStr.",".
                        preg_replace('/ETABLISSEMENT-CDP([0-9]+)01$/',"CDP$1",$etablissement->_id).",".
                        $type_chai.",".
                        $activites.",".
                        $chaiDistant->etablissement_nom.",".
                        trim($chaiDistant->nom).",".
                        trim(str_replace('"', '', $adresses[0])).",".
                        trim(str_replace('"', '', $a_comp)).",".
                        trim(str_replace('"', '', $a_comp1)).",".
                        $chaiDistant->code_postal.",".
                        $this->protectIso($chaiDistant->commune).",".
                        $this->transformNom($etablissement->raison_sociale).",".
                        $telephone.",".
                        ",,".$isArchivee.",,,,".$etablissement->siret.','.
                        Organisme::getCurrentOrganisme().",".
                        $etablissement->_id.
                        "\n";
                        }
                }
        }
    }

    private function transformActivites($activites){
        $a_res = array();
        foreach ($activites as $aKey => $a) {
            $a_res[] = $this->activitesCorespondance[$a];
        }
        uasort($a_res, "exportChaisCsvTask::sortActivite");

        return $a_res;
        }

        private function isArchiveeChai($chais){
            return ($chais->archive)? 'Vrai' : 'Faux';
        }

        public static function sortActivite($a,$b){
            if(!isset(self::$activiteOrdre[$a])) {
                
                return false;
            }
            
            if(!isset(self::$activiteOrdre[$b])) {
                
                return true;
            }
            return self::$activiteOrdre[$a] > self::$activiteOrdre[$b];
        }

        public function protectIso($str){
            return str_replace(array('œ'),array('oe'),$str);
        }

        private function transformNom($nom_or_raison_sociale){
            $matches = array();
            if(preg_match("/(.+) (\(M\)|\(MME\)|\(MM\))$/",$nom_or_raison_sociale,$matches)){
                return str_replace(array('(',')'),array('',''),$matches[2])." ".$matches[1];
            }
            return $nom_or_raison_sociale;
        }
}
