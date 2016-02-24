<?php 

class CompteCsvFile extends CsvFile 
{

    const CSV_ID = 0;
    const CSV_ID_SOCIETE = 1;
    const CSV_STATUT = 3;
    const CSV_CIVILTE = 4;
    const CSV_NOM = 5;
    const CSV_PRENOM = 6;
    const CSV_FONCTION = 7;
    const CSV_ADRESSE = 8;
    const CSV_ADRESSE_COMPLEMENTAIRE_1 = 9;
    const CSV_ADRESSE_COMPLEMENTAIRE_2 = 10;
    const CSV_ADRESSE_COMPLEMENTAIRE_3 = 11;
    const CSV_CODE_POSTAL = 12;
    const CSV_COMMUNE = 13;
    const CSV_INSEE = 14;
    const CSV_CEDEX = 15;
    const CSV_PAYS = 16;
    const CSV_EMAIL = 17;
    const CSV_TEL_BUREAU = 18;
    const CSV_TEL_PERSO = 19;
    const CSV_MOBILE = 20;
    const CSV_FAX = 21;
    const CSV_WEB = 22;
    const CSV_COMMENTAIRE = 23;

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

                $societe = SocieteClient::getInstance()->find(sprintf("SOCIETE-%06d", $line[self::CSV_ID_SOCIETE]), acCouchdbClient::HYDRATE_JSON);
                
                if(!$societe) {

                    throw new sfException(sprintf("Societe introuvable '%s'", sprintf("SOCIETE-%06d", $line[self::CSV_ID_SOCIETE])));
                }
                
              	$c = CompteClient::getInstance()->createCompteFromSociete($societe);

                $c->statut = ($line[self::CSV_STATUT] == SocieteClient::STATUT_SUSPENDU) ? $line[self::CSV_STATUT] : $societe->statut;
        	   
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
                $c->email = $this->formatAndVerifyEmail($line[self::CSV_EMAIL]);
                $c->fax = $this->formatAndVerifyPhone($line[self::CSV_FAX]);
                $c->telephone_perso = $this->formatAndVerifyPhone($line[self::CSV_TEL_PERSO]);
                $c->telephone_bureau = $this->formatAndVerifyPhone($line[self::CSV_TEL_BUREAU]);
                $c->telephone_mobile = $this->formatAndVerifyPhone($line[self::CSV_MOBILE]);
                if($line[self::CSV_WEB]) {
                    $c->add('site_internet', $line[self::CSV_WEB]);
                }

                $c->save();
        	} catch(Execption $e) {
                $this->error[] = $e->getMessage();
            }
        }

        return $societes;
    }

    public function getErrors() {
      
        return $this->errors;
    }

}
