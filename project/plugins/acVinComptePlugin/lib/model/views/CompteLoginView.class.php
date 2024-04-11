<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteAllView
 * @author mathurin
 */
class CompteLoginView extends acCouchdbView {

    public static function getInstance() {
        return acCouchdbManager::getView('compte', 'login', 'Compte');
    }

    public function findComptesByLogin($login) {
      return $this->client->startkey(array($login))
                      ->endkey(array($login, array()))
                      ->getView($this->design, $this->view)->rows;
    }

    public function getAllLogins() {
        $logins = array();
        $rows = $this->client->getView($this->design, $this->view)->rows;
        foreach($rows as $row) {
            $logins[$row->key[0]] = $row->value;
        }

        return $logins;
    }

    public function findOneCompteByLogin($login, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $compte = null;
      foreach ($this->findComptesByLogin($login) as $k => $v) {
        $compte = CompteClient::getInstance()->find($v->id, $hydrate);
        break;
      }
      return $compte;
    }

}
