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

                $this->storeCompteInfos($c, $line);

                $societe->pushToCompteOrEtablissementAndSave($societe->getMasterCompte(), $c);

                echo "Compte " . $c->_id ." créé\n";
        	} catch(Exception $e) {
            if (isset($this->options['throw_exception']) && $this->options['throw_exception']) {
              throw $e;
            }else{
              echo $e->getMessage()."\n";
            }
          }
        }

        return $societes;
    }

    protected function storeCompteInfos(InterfaceCompteGenerique $c, $line, $warningsociete = true) {
        $c->setAdresseComplementaire(null);
        $c->adresse = trim(preg_replace('/,/', '', $this->getField($line, 'CSV_ADRESSE')));

        if(preg_match('/^(.+)\\\n(.+)$/', $c->adresse, $matches)) {
            $c->adresse = $matches[1];
            $c->setAdresseComplementaire($matches[2]);
        }

        if(preg_match('/[a-z]/i', $this->getField($line, 'CSV_ADRESSE_COMPLEMENTAIRE_1'))) {
            if($c->getAdresseComplementaire()) {
                $c->setAdresseComplementaire($c->getAdresseComplementaire(). " ; ");
            }
            $c->setAdresseComplementaire($c->getAdresseComplementaire().trim(preg_replace('/,/', '', $this->getField($line, 'CSV_ADRESSE_COMPLEMENTAIRE_1'))));
            if(preg_match('/[a-z]/i', $this->getField($line, 'CSV_ADRESSE_COMPLEMENTAIRE_2'))) {
                $c->setAdresseComplementaire($c->getAdresseComplementaire(). " ; ".trim(preg_replace('/,/', '', $this->getField($line, 'CSV_ADRESSE_COMPLEMENTAIRE_2'))));
            }
        }

        if($this->getField($line, 'CSV_CEDEX')) {
            $c->adresse_complementaire .= (($c->adresse_complementaire) ?  " ; " : null).$this->getField($line, 'CSV_CEDEX');
        }

        $c->code_postal = trim($this->getField($line, 'CSV_CODE_POSTAL'));

        if(!$c->code_postal && $warningsociete) {
            echo "WARNING: le code postal est vide pour la société ".$c->identifiant."\n";
        }

        if($c->code_postal && !preg_match("/^[0-9]{5}$/", $c->code_postal)) {
            echo "WARNING: le code postal ne semple pas correct : ".$c->code_postal." pour la société ".$c->identifiant."\n";
        }

        $c->commune = $this->getField($line, 'CSV_COMMUNE');
        $c->insee = $this->getField($line, 'CSV_INSEE');

        if(!$c->commune && $warningsociete) {
            echo "WARNING: la commune est vide pour la société ".$c->identifiant.":".implode(";", $line)."\n";
        }

        $c->pays = "";

        if(preg_match("/^FRANCE$/i", $this->getField($line, 'CSV_PAYS'))) {
            $c->pays = 'FR';
        }

        if(!$c->pays) {
            $pays = ConfigurationClient::getInstance()->findCountry($this->getField($line, 'CSV_PAYS'));
            if($pays) {
                $c->pays = $pays;
            } else {
                echo "WARNING: le pays ".$this->getField($line, 'CSV_PAYS')." n'a pas été trouvé pour la société ".$c->identifiant.":".implode(";", $line)."\n";
            }
        }

        $c->email = $this->formatAndVerifyEmail($this->getField($line, 'CSV_EMAIL'), $c);
        $c->fax = $this->formatAndVerifyPhone($this->getField($line, 'CSV_FAX'), $c);
        $c->telephone_perso = $this->formatAndVerifyPhone($this->getField($line, 'CSV_TEL_PERSO'), $c);
        $c->telephone_bureau = $this->formatAndVerifyPhone($this->getField($line, 'CSV_TEL_BUREAU'), $c);
        $c->telephone_mobile = $this->formatAndVerifyPhone($this->getField($line, 'CSV_MOBILE'), $c);
        $c->site_internet = null;
        if($this->getField($line, 'CSV_WEB')) {
            if (preg_match('/^http:\/\/[^ ]+$/', $this->getField($line, 'CSV_WEB'))) {
                $c->site_internet = $this->getField($line, 'CSV_WEB');
            }else{
                if (preg_match('/www.[^ ]+$/', $this->getField($line, 'CSV_WEB'))) {
                    $c->site_internet = 'http://'.$this->getField($line, 'CSV_WEB');
                }else{
                    echo("WARNING: ".$c->identifiant.": site non valide : \"".$this->getField($line, 'CSV_WEB')."\"\n");
                    $c->addCommentaire("Problème d'import, site non valide : \"".$this->getField($line, 'CSV_WEB')."\"");
                }
            }
        }
    }

    protected function getField($line, $strConstant) {
        $constante = constant("self::$strConstant");

        return $line[$constante];
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

    public static function getCsvHeader() {
        $csv = "identifiant;login;nom complet;type;intitule;raison_sociale;fonction;civilite;nom;prénom;adresse;adresse complémentaire;code postal;commune;pays;téléphone bureau;téléphone mobile;téléphone perso;fax;email;commentaire;société identifiant;société type;société raison sociale;société adresse;société adresse complémentaire;société code postal;société commune;société téléphone;société fax;société email;code de création;statut;";

        foreach(SocieteConfiguration::getInstance()->getExtras() as $key => $item) {
            $csv .= $key.';';
        }

        return $csv."droits;tags automatiques;tags documents;tags produits;tags manuels;url;id_couchdb origine\n";
    }

    public static function toCsvLigne($compte, $virtuel = false) {
        $societe_informations = $compte->societe_informations;

        $csv = null;
        $csv .= '"'.$compte->identifiant. ($virtuel ? '_VIRTUEL' : null).'";';
        $csv .= '"'.$compte->identifiant. '";';
        $csv .= '"'.str_replace('"', '', $compte->nom_a_afficher). '";';
        $csv .= '"'.CompteClient::getInstance()->createTypeFromOrigines($compte->origines).($virtuel ? '_VIRTUEL' : null).'";';
        $csv .= '"'.($compte->compte_type != CompteClient::TYPE_COMPTE_INTERLOCUTEUR ? CompteGenerique::extractIntitule($compte->nom)[0] : null). '";';
        $csv .= '"'.($compte->compte_type != CompteClient::TYPE_COMPTE_INTERLOCUTEUR ? str_replace('"', '', CompteGenerique::extractIntitule($compte->nom)[1]) : null). '";';
        $csv .= '"'.$compte->fonction. '";';
        $csv .= '"'.($compte->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR ? $compte->civilite : null). '";';
        $csv .= '"'.($compte->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR ? str_replace('"', '', $compte->nom) : null). '";';
        $csv .= '"'.($compte->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR ? $compte->prenom : null). '";';
        $csv .= '"'.str_replace('"', '', $compte->adresse). '";';
        $csv .= '"'.str_replace('"', '', $compte->adresse_complementaire). '";';
        $csv .= '"'.$compte->code_postal. '";';
        $csv .= '"'.$compte->commune. '";';
        $csv .= '"'.$compte->pays. '";';
        $csv .= '"'.$compte->telephone_bureau. '";';
        $csv .= '"'.$compte->telephone_mobile. '";';
        $csv .= '"'.$compte->telephone_perso. '";';
        $csv .= '"'.$compte->fax. '";';
        $csv .= '"'.$compte->email. '";';
        $csv .= '"'.str_replace('"', '', $compte->commentaire). '";';
        $csv .= '"'.str_replace('SOCIETE-', '', $compte->id_societe). '";';
        $csv .= '"'.$compte->societe_informations->type. '";';
        $csv .= '"'.str_replace('"', '', $compte->societe_informations->raison_sociale). '";';
        $csv .= '"'.str_replace('"', '', $compte->societe_informations->adresse). '";';
        $csv .= '"'.str_replace('"', '', $compte->societe_informations->adresse_complementaire). '";';
        $csv .= '"'.$compte->societe_informations->code_postal. '";';
        $csv .= '"'.$compte->societe_informations->commune. '";';
        $csv .= '"'.$compte->societe_informations->telephone. '";';
        $csv .= '"'.$compte->societe_informations->fax. '";';
        $csv .= '"'.$compte->societe_informations->email. '";';
        $statutCreationCompte = str_replace("{TEXT}", "", $compte->mot_de_passe);
        if($compte->mot_de_passe && strpos($compte->mot_de_passe, '{TEXT}') === false) {
            $statutCreationCompte = "COMPTE_CREE";
        } elseif(!$compte->mot_de_passe) {
            $statutCreationCompte = "CODE_NON_GENERE";
        }
        $csv .= '"'.$statutCreationCompte.'";';
        $csv .= '"'.$compte->statut. '";';

        foreach(SocieteConfiguration::getInstance()->getExtras() as $key => $item) {
            $value = null;
            if(isset($compte->extras->{$key})) {
                $value = str_replace('"', '', $compte->extras->{$key});
            }
            $csv .= '"'.str_replace('SOCIETE-', '', $value).'";';
        }

        $csv .= '"'.(isset($compte->droits) ? implode("|", $compte->droits) : null).'";';
        $csv .= '"'.(isset($compte->tags->automatique) ? implode("|", $compte->tags->automatique) : null).'";';
        $csv .= '"'.(isset($compte->tags->documents) ? implode("|", $compte->tags->documents) : null).'";';
        $csv .= '"'.(isset($compte->tags->produits) ? implode("|", $compte->tags->produits) : null).'";';
        $csv .= '"'.(isset($compte->tags->manuel) ? implode("|", $compte->tags->manuel) : null).'";';
        $csv .= '"'.ProjectConfiguration::getAppRouting()->generate('societe_visualisation', array('identifiant' => str_replace('SOCIETE-', '', $compte->id_societe)), true).'";';
        $csv .= (isset($compte->origines[0]) ? $compte->origines[0] : $compte->_id).';';
        $csv .= "\n";

        return $csv;
    }

}
