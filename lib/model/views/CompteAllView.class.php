<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteAllView
 * @author mathurin
 */
class CompteAllView extends acCouchdbView {

    const KEY_INTERPRO_ID = 0;
    const KEY_ID = 1;
    const KEY_NOM_A_AFFICHER = 2;
    const KEY_IDENTIFIANT = 3;
    const KEY_ADRESSE = 4;
    const KEY_COMMUNE = 5;
    const KEY_CODE_POSTAL = 6;
    
    public static function getInstance() {

        return acCouchdbManager::getView('compte', 'all', 'Compte');
    }

    public function findByInterpro($interpro) {

        return $this->client->startkey(array($interpro))
                        ->endkey(array($interpro, array()))
                        ->getView($this->design, $this->view);
    }

    public function findByInterproAndId($interpro,$id) {

        return $this->client->startkey(array($interpro,$id))
                        ->endkey(array($interpro,$id, array()))
                        ->getView($this->design, $this->view);
    }
    
    public static function makeLibelle($datas) {
        $libelle = '';

        if (isset($datas[self::KEY_NOM_A_AFFICHER]) && $nom = $datas[self::KEY_NOM_A_AFFICHER]) {
            if ($libelle) {
                $libelle .= ' / ';
            }
            $libelle .= $nom;
        }

        $libelle .= ' (' . $datas[self::KEY_ADRESSE];
        if (isset($datas[self::KEY_ADRESSE]) && $adresse = $datas[self::KEY_ADRESSE]) {
            $libelle .= ' / ' . $adresse;
        }

        if (isset($datas[self::KEY_COMMUNE]) && $commune = $datas[self::KEY_COMMUNE]) {
            $libelle .= ' / ' . $commune;
        }
        
        if (isset($datas[self::KEY_CODE_POSTAL]) && $cp = $datas[self::KEY_CODE_POSTAL]) {
            $libelle .= ' / ' . $cp;
        }
        $libelle .= ') ';

        return trim($libelle);
    }

}

