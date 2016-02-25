<?php 

class CompteCsvFile extends CsvFile 
{

    const CSV_ID = 0;
    const CSV_ID_SOCIETE = 1;
    const CSV_STATUT = 2;
    const CSV_CIVILTE = 3;
    const CSV_NOM = 4;
    const CSV_PRENOM = 5;
    const CSV_FONCTION = 6;
    const CSV_ADRESSE = 7;
    const CSV_ADRESSE_COMPLEMENTAIRE_1 = 8;
    const CSV_ADRESSE_COMPLEMENTAIRE_2 = 9;
    const CSV_ADRESSE_COMPLEMENTAIRE_3 = 10;
    const CSV_CODE_POSTAL = 11;
    const CSV_COMMUNE = 12;
    const CSV_INSEE = 13;
    const CSV_CEDEX = 14;
    const CSV_PAYS = 15;
    const CSV_EMAIL = 16;
    const CSV_TEL_BUREAU = 17;
    const CSV_TEL_PERSO = 18;
    const CSV_MOBILE = 19;
    const CSV_FAX = 20;
    const CSV_WEB = 21;
    const CSV_COMMENTAIRE = 22;

    public function importComptes() {
        $this->errors = array();
        $societes = array();
        $csvs = $this->getCsv();
        foreach ($csvs as $line) {
            try{
        	    if($line[self::CSV_ID]) {
                      if (CompteClient::getInstance()->find($line[self::CSV_ID], acCouchdbClient::HYDRATE_JSON)) {
                          echo "ERROR: Compte ".$id." existe\n";
                          continue;
                      }
                }

                if(!$line[self::CSV_ID]) {

                }

                $societe = SocieteClient::getInstance()->find(sprintf("SOCIETE-%06d", $line[self::CSV_ID_SOCIETE]));
                
                if(!$societe) {

                    throw new sfException(sprintf("Societe introuvable '%s'", sprintf("SOCIETE-%06d", $line[self::CSV_ID_SOCIETE])));
                }
                
              	$c = CompteClient::getInstance()->createCompteFromSociete($societe);

                $c->statut = ($line[self::CSV_STATUT] == SocieteClient::STATUT_SUSPENDU) ? $line[self::CSV_STATUT] : $societe->statut;

                $c->civilite = $line[self::CSV_CIVILTE];
                $c->nom = $line[self::CSV_NOM];
                $c->prenom = $line[self::CSV_PRENOM];
                $c->fonction = $line[self::CSV_FONCTION];

                $c->adresse = null;
                $c->adresse_complementaire = null;
        	   
                $c->adresse = trim(preg_replace('/,/', '', $line[self::CSV_ADRESSE]));
                if(preg_match('/[a-z]/i', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_1])) {
                    $c->add('adresse_complementaire',trim(preg_replace('/,/', '', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_1])));
                    if(preg_match('/[a-z]/i', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_2])) {
                        $c->adresse_complementaire .= " ; ".trim(preg_replace('/,/', '', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_2]));
                    }
                }
                
                $c->code_postal = trim($line[self::CSV_CODE_POSTAL]);

                if($c->code_postal && !preg_match("/^[0-9]{5}$/", $c->code_postal)) {
                     echo "WARNING: le code postal ne semple pas correct : ".$c->code_postal." pour le compte ".$c->_id."\n";
                }

                $c->commune = $line[self::CSV_COMMUNE];
                $c->insee = $line[self::CSV_INSEE];
                $c->pays = 'FR';
                $c->email = $this->formatAndVerifyEmail($line[self::CSV_EMAIL],$c);
                $c->fax = $this->formatAndVerifyPhone($line[self::CSV_FAX],$c);
                $c->telephone_perso = $this->formatAndVerifyPhone($line[self::CSV_TEL_PERSO],$c);
                $c->telephone_bureau = $this->formatAndVerifyPhone($line[self::CSV_TEL_BUREAU],$c);
                $c->telephone_mobile = $this->formatAndVerifyPhone($line[self::CSV_MOBILE], $c);
                if($line[self::CSV_WEB]) {
                    $c->add('site_internet', $line[self::CSV_WEB]);
                }

                $c->save();

                echo "Compte " . $c->_id ." créé\n";
        	} catch(Exception $e) {
                
               echo $e->getMessage()."\n";
            }
        }

        return $societes;
    }

    public function getErrors() {
      
        return $this->errors;
    }

    protected function formatAndVerifyPhone($phone, $c) {

        $phone = str_replace("+33", "0", trim($phone));
        $phone = preg_replace("/[\._ -]/", "", $phone);

        if($phone && strlen($phone) == 9) {
            $phone = "0".$phone;
        }

        if($phone && !preg_match("/^[0-9]{10}$/", $phone)) {
            printf("WARNING: Problème d'import : Le numéro de téléphone n'est pas correct %s\n", $phone);
            $c->addCommentaire(sprintf("Problème d'import : Le numéro de téléphone n'est pas correct %s", $phone));
            return null;
        }

        return $phone;
    }

    protected function formatAndVerifyEmail($email, $c) {
        $email = trim($email);

        if($email && !preg_match("/^[a-z0-9çéèàâê_\.-]+@[a-z0-9\.-]+$/i", $email)) {
            printf("WARNING: Problème d'import : L'email n'est pas correct %s\n", $email);
            $c->addCommentaire(sprintf("Problème d'import : L'email n'est pas correct %s", $email));
            return null;
        }

        return $email;
    }


}
