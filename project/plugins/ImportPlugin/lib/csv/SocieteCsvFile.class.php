<?php 

class SocieteCsvFile extends CsvFile 
{
    const CSV_ID = 0;
    const CSV_TYPE = 1;
    const CSV_NOM = 2;
    const CSV_NOM_REDUIT = 3;
    const CSV_STATUT = 4;
    const CSV_TYPE_FOURNISSEUR = 5;
    const CSV_SIRET = 6;
    const CSV_CODE_NAF = 7;
    const CSV_TVA_INTRACOMMUNAUTAIRE = 8;
    const CSV_ADRESSE = 9;
    const CSV_ADRESSE_COMPLEMENTAIRE_1 = 10;
    const CSV_ADRESSE_COMPLEMENTAIRE_2 = 11;
    const CSV_ADRESSE_COMPLEMENTAIRE_3 = 12;
    const CSV_CODE_POSTAL = 13;
    const CSV_COMMUNE = 14;
    const CSV_INSEE = 15;
    const CSV_CEDEX = 16;
    const CSV_PAYS = 17;
    const CSV_EMAIL = 18;
    const CSV_TEL_BUREAU = 19;
    const CSV_TEL_PERSO = 20;
    const CSV_MOBILE = 21;
    const CSV_FAX = 22;
    const CSV_WEB = 23;
    const CSV_COMMENTAIRE = 24; 

    private function verifyCsvLine($line) {
        if (!preg_match('/[0-9]+/', $line[self::CSV_ID]) || ((int) $line[self::CSV_ID]) == 0) {

            throw new Exception(sprintf('ID invalide : %s', $line[self::CSV_ID]));
        }
    }

    public function importSocietes () {
        $this->errors = array();
        $societes = array();
        $csvs = $this->getCsv();
        foreach ($csvs as $line) {
            try {
              	$this->verifyCsvLine($line);
                $id = sprintf("%06d", $line[self::CSV_ID]);

              	$s = SocieteClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
                if ($s) {
        	          echo "ERROR: Societe existe ".$id."\n";
        	          continue;
                }

              	$s = new Societe();
                $s->identifiant = $id;
                $s->constructId();
                $s->raison_sociale = trim($line[self::CSV_NOM]);
        	    $s->raison_sociale_abregee = trim($line[self::CSV_NOM_REDUIT]);
              	$s->interpro = 'INTERPRO-declaration';
                $s->siret = str_replace(" ", "", $line[self::CSV_SIRET]);
                $s->code_naf = str_replace(" ", "", $line[self::CSV_CODE_NAF]);
                $s->no_tva_intracommunautaire = str_replace(" ", "", $line[self::CSV_TVA_INTRACOMMUNAUTAIRE]);
                $s->commentaire = $line[self::CSV_COMMENTAIRE];
                /*if ($line[self::CSV_COOPGROUP] == 'C') {
              		$s->cooperative = 1;
                }*/
                $s->statut = $line[self::CSV_STATUT];
                $s->type_societe = $line[self::CSV_TYPE];
  	            /*if ($line[self::CSV_ENSEIGNE]) {
	                $s->enseignes->add(null, $line[self::CSV_ENSEIGNE]);
                }*/
                /*if($line[self::CSV_CODE_FOURNISSEUR]){
                    $s->code_comptable_fournisseur = sprintf('%08d', $line[self::CSV_CODE_FOURNISSEUR]);                
                }*/
                /*$s->add('type_fournisseur', array());
                if($line[self::CSV_CODE_FOURNISSEUR]){
                    $fournisseur_tag = preg_replace ('/([A-Za-z ]*)(MDV|PLV)/','$2',$line[self::CSV_CODE_FOURNISSEUR]);
                    $s->add('type_fournisseur',array($fournisseur_tag));
                }*/
              	$s->save();

                $c = $s->getContact();
                $c->adresse = trim(preg_replace('/,/', '', $line[self::CSV_ADRESSE]));
                if(preg_match('/[a-z]/i', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_1])) {
                    $c->add('adresse_complementaire',trim(preg_replace('/,/', '', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_1])));
                    if(preg_match('/[a-z]/i', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_2])) {
                        $c->adresse_complementaire .= " ; ".trim(preg_replace('/,/', '', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_2]));
                    }
                }

                if($line[self::CSV_CEDEX]) {
                    $c->adresse_complementaire .= (echo ($c->adresse_complementaire) ?  " ; " : null).$line[self::CSV_CEDEX];
                }

                $c->code_postal = trim($line[self::CSV_CODE_POSTAL]);

                if(!$c->code_postal) {
                    echo "WARNING: le code postal est vide pour la société ".$s->_id."\n";
                }

                if($c->code_postal && !preg_match("/^[0-9]{5}$/", $c->code_postal)) {
                    echo "WARNING: le code postal ne semple pas correct : ".$c->code_postal." pour la société ".$s->_id."\n";
                }

                $c->commune = $line[self::CSV_COMMUNE];
                $c->insee = $line[self::CSV_INSEE];

                if(!$c->commune) {
                    echo "WARNING: la commune (".$c->insee.") est vide pour la société ".$s->_id.":".implode(";", $line)."\n";
                }

                $c->pays = 'FR';
                $c->email = $this->formatAndVerifyEmail($line[self::CSV_EMAIL], $c);
                $c->fax = $this->formatAndVerifyPhone($line[self::CSV_FAX], $c);
                $c->telephone_perso = $this->formatAndVerifyPhone($line[self::CSV_TEL_PERSO], $c);
                $c->telephone_bureau = $this->formatAndVerifyPhone($line[self::CSV_TEL_BUREAU], $c);
                $c->telephone_mobile = $this->formatAndVerifyPhone($line[self::CSV_MOBILE], $c);
                if($line[self::CSV_WEB]) {
                    if (preg_match('/^http:\/\/[^ ]+$/', $line[self::CSV_WEB])) {
                        $c->add('site_internet', $line[self::CSV_WEB]);
                    }else{
                        if (preg_match('/www.[^ ]+$/', $line[self::CSV_WEB])) {
                            $c->add('site_internet', 'http://'.$line[self::CSV_WEB]);
                        }else{
                            echo("WARNING: ".$s->_id.": site non valide : \"".$line[self::CSV_WEB]."\"\n");
                            $c->addCommentaire("Problème d'import, site non valide : \"".$line[self::CSV_WEB]."\"");
                        }
                    }
                }
                $c->save();

            }catch(Exception $e) {
                echo $e->getMessage()."\n";
                $this->error[] = $e->getMessage();
            }
        }

        return $societes;
    } 

    public function getErrors() {
        return $this->errors;
    }
  
    protected function convertCountry($country) {
        $countries = ConfigurationClient::getInstance()->getCountryList();

        if($country == 'FRA') {
          $country = 'FR';
        }

        if($country == 'TU') {
          $country = 'TR';
        }

        if(!array_key_exists($country, $countries)) {
          
          throw new sfException(sprintf("Code pays '%s' invalide", $country));
        }
        
        return $country;
    }

    protected function formatAndVerifyPhone($phone, $c) {

        $phone = str_replace("+33", "0", trim($phone));
        $phone = preg_replace("/[\._ -]/", "", $phone);

        if($phone && strlen($phone) == 9) {
            $phone = "0".$phone;
        }

        if($phone && !preg_match("/^[0-9]{10}$/", $phone) && !preg_match("/^00/", $phone)) {
            printf("WARNING: ".$c->_id.": Problème d'import : Le numéro de téléphone n'est pas correct %s\n", $phone);
            $c->addCommentaire(sprintf("Problème d'import : Le numéro de téléphone n'est pas correct %s", $phone));
            return null;
        }

        return $phone;
    }

    protected function formatAndVerifyEmail($email, $c) {
        $email = trim($email);

        if($email && !preg_match("/^[a-z0-9çéèàâê_\.-]+@[a-z0-9\.-]+$/i", $email)) {
            printf("WARNING: ".$c->_id.": L'email n'est pas correct %s\n", $email);
            $c->addCommentaire(sprintf("Problème d'import: L'email n'est pas correct %s", $email));
            return null;
        }

        return $email;
    }



}
