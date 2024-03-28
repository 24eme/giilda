<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteAllView
 * @author mathurin
 */
class CompteTagsView extends acCouchdbView {

    const KEY_TYPE_TAG = 0;
    const KEY_TAG = 1;
    const KEY_LIBELLEWITHADRESSE_COMPTE = 2;
    
    public static function getInstance() {
        return acCouchdbManager::getView('compte', 'tags', 'Compte');
    }

    public function listByTags($type, $tag) {
      return $this->client->startkey(array($type, $tag))
                      ->endkey(array($type, $tag, array()))
                      ->getView($this->design, $this->view)->rows;
    }

    public function findOneCompteByTag($type, $tag) {
      $compte = null;
      foreach ($this->listByTags($type, $tag) as $k => $v) {
        $compte = CompteClient::getInstance()->find($v->id);
        break;
      }
      return $compte;
    }

}
