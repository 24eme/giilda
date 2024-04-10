<?php
class CompteLdap extends acVinLdap
{
    public $ou = 'ou=People';
    public $id = 'uid';

    public function saveCompte($compte, $verbose = 0)
    {
        $info = $this->info($compte);
        if ($verbose) {
            echo "save : ";
            print_r($info);
        }
        return $this->save(self::getIdentifiant($compte), $info);
    }

    /**
     *
     * @param _Compte $compte
     * @return bool
     */
    public function deleteCompte($compte, $verbose = 0)
    {
        if (is_string($compte)) {
            $identifiant = $compte;
        }else {
            $identifiant = self::getIdentifiant($compte);
        }
        if ($verbose) {
            echo $identifiant." deleted\n";
        }
        return $this->delete($identifiant);
    }

    public static function getIdentifiant($compte)
    {
        $login = $compte->login;

        if(!$login) {

            return $compte->identifiant;
        }

        return $login;
    }

    /**
     *
     * @param _Compte $compte
     * @return array
     */
    protected function info($compte)
    {
        $info = array();
        $info['uid']              = self::getIdentifiant($compte);
        $info['cn']               = $compte->nom_a_afficher;
        $info['objectClass'][0]   = 'top';
        $info['objectClass'][1]   = 'person';
        $info['objectClass'][2]   = 'posixAccount';
        $info['objectClass'][3]   = 'inetOrgPerson';
        $info['loginShell']       = '/bin/bash';
        $info['uidNumber']        = (int)self::getIdentifiant($compte);
        $info['gidNumber']        = '1000';
        $info['homeDirectory']    = '/home/'.$compte->_id;
        $info['gecos']            = self::getGecos($compte);
        if ($compte->isEtablissementContact()) {
             $info['businessCategory'] = $compte->getEtablissement()->famille;
        }

        $info['description']      = ($compte->societe_informations->type)? $compte->societe_informations->type : '';
        $info['sn'] = ($compte->getNom()) ?: $compte->nom_a_afficher;
        if (!isset($info['o']) || !$info['o']) {
            $info['o'] = $info['sn'];
        }

        if ($compte->getPrenom()) {
            $info['givenName']        = $compte->getPrenom();
        }

        if ($compte->email && filter_var($compte->email, FILTER_VALIDATE_EMAIL)) {
            $info['mail']             = $compte->email;
        }

        if ($compte->adresse) {
            $info['street']           = preg_replace('/;/', '\n', $compte->adresse);
            if ($compte->adresse_complementaire) {
                $info['street']        .= " \n ".preg_replace('/;/', '\n', $compte->adresse_complementaire);
            }
        }

        if ($compte->commune) {
            $info['l']                = trim($compte->commune);
        }

        if ($compte->code_postal) {
            $info['postalCode']       = trim($compte->code_postal);
        }

        if ($compte->telephone_bureau) {
            $info['telephoneNumber']  = trim(str_replace("_", "", $compte->telephone_bureau));
        }

        if ($compte->telephone_mobile) {
            $info['mobile']           = trim(str_replace("_", "", $compte->telephone_mobile));
        }

        if ($compte->exist('mot_de_passe')) {
            $info['userPassword']  = $compte->mot_de_passe;
            if (!$compte->isActif()) {
                $info['userPassword'] = null;
            }
        }

        return $info;
    }

    public static function getGecos($compte) {

        if($compte->exist('gecos') && $compte->gecos) {
            return $compte->gecos;
        }

        $etablissement = $compte->getEtablissement();
        if(!$etablissement && $compte->getSociete()) {
            $etablissement = $compte->getSociete()->getEtablissementPrincipal();
        }
        $gecos = null;

        if(!$etablissement) {
            $gecos = sprintf("%s,%s,%s,%s,%s:%s", $compte->identifiant, null, ($compte->getNom()) ? $compte->getNom() : $compte->nom_a_afficher, $compte->nom_a_afficher, 'giilda', $compte->_id);
        }

        //Hack pour la compatibilité GAMMAlsace du CIVA
        if (!$gecos && class_exists('civaConfiguration')) {
            $gamma = acCouchdbManager::getClient()->find(str_replace('ETABLISSEMENT', 'GAMMA', $etablissement->_id), acCouchdbClient::HYDRATE_JSON);
            if ($gamma) {
                $gecos =  sprintf("%s,%s,%s,%s,%s:%s", $gamma->identifiant_inscription, $gamma->no_accises, ($compte->getNom()) ? $compte->getNom() : $compte->nom_a_afficher, $compte->nom_a_afficher, 'giilda', $gamma->_id);
            }
        }

        if (!$gecos) {
            $gecos =  sprintf("%s,%s,%s,%s,%s:%s", $compte->identifiant, $etablissement->no_accises, ($compte->getNom()) ? $compte->getNom() : $compte->nom_a_afficher, $compte->nom_a_afficher, 'giilda', $etablissement->_id);
        }

        $gecos = str_replace(array('é', 'è', 'ê', 'ë', 'à', 'ù', 'ä', 'ü', 'ï', 'ç', 'ö', 'ô', 'â', 'î', 'ô', 'û'),
                             array('e', 'e', 'e', 'e', 'a', 'u', 'a', 'u', 'i', 'c', 'o', 'o', 'a', 'i', 'o', 'u'), $gecos);
        return $gecos;
    }

}
