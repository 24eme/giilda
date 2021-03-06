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
        if ($compte->isSocieteContact()) {
            return $compte->getSociete()->identifiant;
        } else {
            return $compte->identifiant;
        }
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
        $info['homeDirectory']    = '/home/'.self::getIdentifiant($compte);
        $info['o']                = $compte->getSociete()->raison_sociale;
        $info['description']      = ($compte->societe_informations->type)? $compte->societe_informations->type : '';
        $info['sn'] = ($compte->getNom()) ?: $compte->nom_a_afficher;

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
            $info['l']                = $compte->commune;
        }

        if ($compte->code_postal) {
            $info['postalCode']       = $compte->code_postal;
        }

        if ($compte->telephone_bureau) {
            $info['telephoneNumber']  = $compte->telephone_bureau;
        }

        if ($compte->fax) {
            $info['facsimileTelephoneNumber'] = $compte->fax;
        }

        if ($compte->telephone_mobile) {
            $info['mobile']           = $compte->telephone_mobile;
        }

        if ($compte->exist('mot_de_passe')) {
            $info['userPassword']  = $compte->mot_de_passe;
            if (!$compte->isActif()) {
                $info['userPassword'] = null;
            }
        }

        return $info;
    }
}
