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
    const CSV_CEDEX = 15;
    const CSV_PAYS = 16;
    const CSV_EMAIL = 17;
    const CSV_TEL_BUREAU = 18;
    const CSV_TEL_PERSO = 19;
    const CSV_MOBILE = 20;
    const CSV_FAX = 21;
    const CSV_WEB = 22;
    const CSV_COMMENTAIRE = 23; 

    private function verifyCsvLine($line) {
        if (!preg_match('/[0-9]+/', $line[self::CSV_ID])) {

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
                print_r($line);
                $id = sprintf("%06d", $line[self::CSV_ID]);

              	$s = SocieteClient::getInstance()->find($id, acCouchdbClient::HYDRATE_JSON);
                if ($s) {
        	          echo "ERROR: Societe exists (".$id.")\n";
        	          continue;
                }

              	$s = new Societe();
                $s->identifiant = $id;
                $s->constructId();
                $s->raison_sociale = $line[self::CSV_NOM];
        	    $s->raison_sociale_abregee = $line[self::CSV_NOM_REDUIT];
              	$s->interpro = 'INTERPRO-declaration';
                $s->siret = $line[self::CSV_SIRET];
                $s->code_naf = $line[self::CSV_CODE_NAF];
                $s->no_tva_intracommunautaire = $line[self::CSV_TVA_INTRACOMMUNAUTAIRE];
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
                echo $s->_id."\n";                

                $c = $s->getContact();
                $c->adresse = preg_replace('/,/', '', $line[self::CSV_ADRESSE]);
                if(preg_match('/[a-z]/i', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_1])) {
                    $c->add('adresse_complementaire',preg_replace('/,/', '', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_1]));
                    if(preg_match('/[a-z]/i', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_2])) {
                        $c->adresse_complementaire .= " ; ".preg_replace('/,/', '', $line[self::CSV_ADRESSE_COMPLEMENTAIRE_2]);
                    }
                }
                $c->code_postal = $line[self::CSV_CODE_POSTAL];
                $c->commune = $line[self::CSV_COMMUNE];
                $c->pays = 'FR';
                $c->email = $line[self::CSV_EMAIL];
                $c->fax = $line[self::CSV_FAX];
                $c->telephone_perso =  $line[self::CSV_TEL_PERSO];
                $c->telephone_bureau = $line[self::CSV_TEL_BUREAU];
                $c->telephone_mobile = $line[self::CSV_MOBILE];
                if($line[self::CSV_WEB]) {
                    $c->add('site_internet', $line[self::CSV_WEB]);
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



}
