<?php

class CompteClient extends acCouchdbClient {

    const TYPE_COMPTE_SOCIETE = "SOCIETE";
    const TYPE_COMPTE_ETABLISSEMENT = "ETABLISSEMENT";
    const TYPE_COMPTE_INTERLOCUTEUR = "INTERLOCUTEUR";
    const STATUT_ACTIF = "ACTIF";
    const STATUT_SUSPENDU = "SUSPENDU";
    const STATUT_SUPPRIME = "SUPPRIME";

    const STATUT_TELEDECLARANT_NOUVEAU = "NOUVEAU";
    const STATUT_TELEDECLARANT_INSCRIT = "INSCRIT";
    const STATUT_TELEDECLARANT_OUBLIE = "OUBLIE";
    const STATUT_TELEDECLARANT_INACTIF = "INACTIF";

    const API_ADRESSE_URL = "https://api-adresse.data.gouv.fr/search/";

    public static $statutsLibelles = array( self::STATUT_ACTIF => "Actif",
                                           self::STATUT_SUSPENDU => "Archivé");

    public static function getInstance() {
        return acCouchdbManager::getClient("Compte");
    }

    public function getId($id_or_identifiant) {
        if (strpos($id_or_identifiant, 'COMPTE-') === 0 ) {
            return $id_or_identifiant;
        }
        if (! (intval($id_or_identifiant))) {
            return 'COMPTE-' . $id_or_identifiant;
        }
        if (strpos($id_or_identifiant, '0') === 0) {
            return 'COMPTE-' . $id_or_identifiant;
        }
        return 'COMPTE-' . sprintf('%08d', $id_or_identifiant);
    }

    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT, $force_return_ls = false) {
        return parent::find($this->getId($id_or_identifiant), $hydrate, $force_return_ls);
    }

    public function getNextIdentifiantForEtablissementInSociete($societe) {
        $societe_id = $societe->identifiant;
        $comptes = self::getAtSociete($societe_id, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        $last_num = 0;
        foreach ($comptes as $id) {
            if (!preg_match('/COMPTE-'.SocieteClient::getInstance()->getSocieteFormatIdentifiantRegexp().'([0-9]{2})/', $id, $matches)) {
                continue;
            }
            $num = $matches[3];
            if($num > 9){
              continue;
            }
            if ($num > $last_num) {
                $last_num = $num;
            }
        }
        return sprintf("%06d%02d", $societe_id, $last_num + 1);
    }

    public function getNextIdentifiantInterlocuteurForSociete($societe) {
        $societe_id = $societe->identifiant;
        $comptes = self::getAtSociete($societe_id, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        $last_num = 9;
        foreach ($comptes as $id) {
            if (!preg_match('/COMPTE-'.SocieteClient::getInstance()->getSocieteFormatIdentifiantRegexp().'([0-9]{2})/', $id, $matches)) {
                continue;
            }

            $num = $matches[3];
            if ($num > $last_num) {
                $last_num = $num;
            }
        }
        return sprintf("%s%02d", $societe_id, $last_num + 1);
    }

    public function getAtSociete($societe_id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('COMPTE-' . $societe_id . '00')->endkey('COMPTE-' . $societe_id . '99')->execute($hydrate);
    }

    public function findByIdentifiant($identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return $this->find($this->getId($identifiant), $hydrate);
    }

    public function findAndDelete($idCompte, $from_etablissement = false, $from_societe = false) {
        $compte = $this->find($idCompte);
        if (!$compte)
            return;
        $this->delete($compte);

        if (!$from_societe) {
            $societe = $compte->getSociete();
            $societe->removeContact($idCompte);
            $societe->save();
        }

        if (!$from_etablissement) {
            throw new sfException("Not yet implemented");
        }
    }

    public function getAllTagsManuel() {
          $q = new acElasticaQuery();
          $elasticaFacet   = new acElasticaFacetTerms('tags');
          $elasticaFacet->setField('doc.tags.manuel');
          $elasticaFacet->setSize(250);
          $q->addFacet($elasticaFacet);

          try {
              $index = acElasticaManager::getType('COMPTE');
              $resset = $index->search($q);
          } catch(Exception $e) {
              return array();
          }

          $results = $resset->getResults();
          $this->facets = $resset->getFacets();

          ksort($this->facets);

          $entries = array();
          foreach ($this->facets["tags"]["buckets"] as $facet) {
              if($facet["key"]){
                $entry = new stdClass();
                $entry->id = trim($facet["key"]);
                $entry->text = trim(str_replace("_",' ',$facet["key"]));
                $entries[] = $entry;
            }
          }
          return $entries;
    }

    public function getAllTagsGroupes($groupesActuels = array()) {
      $q = new acElasticaQuery();
      $elasticaFacet   = new acElasticaFacetTerms('groupes');
      $elasticaFacet->setField('doc.groupes.nom');
      $elasticaFacet->setSize(250);
      $q->addFacet($elasticaFacet);
      try {
          $index = acElasticaManager::getType('COMPTE');
          $resset = $index->search($q);
      } catch(Exception $e) {
          return array();
      }
      $resset = $index->search($q);
      $facets = $resset->getFacets();

      $all_grps = array();
      foreach ($facets as $key  => $ftype) {
        foreach ($ftype['buckets'] as $f) {
          $grpName = $f['key'];
          $entrie = new stdClass();
           $entrie->id = $grpName;
           $entrie->text = $grpName;
           $found = false;
          foreach ($groupesActuels as $grpActKey => $grp) {
             if(Compte::transformTag(sfOutputEscaper::unescape($grp->nom)) == Compte::transformTag(sfOutputEscaper::unescape($grpName))){
              $found = true;
             }
          }
          if(!$found){
            $all_grps[] = $entrie;
          }
        }
      }
      uasort($all_grps, "CompteClient::sortGroupes");
      $all_grps = array_values($all_grps);
      return $all_grps;
    }

    public function createTypeFromOrigines($origines) {
        foreach ($origines as $o) {
            if (preg_match('/SOCIETE/', $o)) {
                return self::TYPE_COMPTE_SOCIETE;
            }
        }

        foreach ($origines as $o) {
            if (preg_match('/ETABLISSEMENT/', $o)) {
                return self::TYPE_COMPTE_ETABLISSEMENT;
            }
        }

        return self::TYPE_COMPTE_INTERLOCUTEUR;
    }

    public static function getGroupesAndFonction($groupesArr,$groupeName){
      foreach ($groupesArr as $grp) {
        if(Compte::transformTag(sfOutputEscaper::unescape($grp['nom'])) == $groupeName){
          return array('nom' => sfOutputEscaper::unescape($grp['nom']), 'fonction' => $grp['fonction']);
        }
      }
      return array('nom' => '', 'fonction' => '');
    }

    public function generateCodeCreation() {

        return sprintf("{TEXT}%04d", rand(1000, 9999));
    }

    public function findOrCreateCompteSociete($societe) {
        $compte = null;
        if ($societe->compte_societe) {
            $compte = $this->find($societe->compte_societe);
        }

        if (!$compte) {
            $compte = $this->createCompteFromSociete($societe);
        }

        return $compte;
    }

    public function findOrCreateCompteFromEtablissement($e) {
        $compte = $this->find($e->getNumCompteEtablissement());

        if (!$compte) {

            $compte = $this->createCompteFromEtablissement($e);
        }

        return $compte;
    }

    public function createCompteFromSociete($societe,$import = false) {
      $compte = new Compte();
      $compte->id_societe = $societe->_id;
      if($import || !$societe->isNew()) {
      $societe->pushContactAndAdresseTo($compte);
      }
      $compte->identifiant = $societe->identifiant;
      $compte->constructId();
      $compte->interpro = 'INTERPRO-declaration';
      $compte->setStatut(CompteClient::STATUT_ACTIF);

      return $compte;
    }

    public function createCompteForEtablissementFromSociete($etablissement,$import = false) {
      $compte = new Compte();
      $compte->id_societe = $etablissement->getSociete()->_id;
      if(!$etablissement->isNew()) {
      $etablissement->pushContactAndAdresseTo($compte);
      }
      $compte->identifiant = $etablissement->identifiant;
      $compte->constructId();
      $compte->interpro = 'INTERPRO-declaration';
      $compte->setStatut(CompteClient::STATUT_ACTIF);

      return $compte;
    }

    public function createCompteInterlocuteurFromSociete($societe) {
        $compte = new Compte();
        $compte->id_societe = $societe->_id;
        $compte->identifiant = $this->getNextIdentifiantInterlocuteurForSociete($societe);
        $compte->constructId();
        $compte->interpro = 'INTERPRO-declaration';
        $compte->setStatut(CompteClient::STATUT_ACTIF);

        return $compte;
    }

    public function createCompteFromEtablissement($etablissement) {
        $compte = $this->createCompteFromSociete($etablissement->getSociete());
        $compte->statut = $etablissement->statut;
        $compte->addOrigine($etablissement->_id);
        $etablissement->pushContactAndAdresseTo($compte);

        return $compte;
    }

    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Compte
     */
    public function retrieveByLogin($login, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->findByLogin($login, $hydrate);
    }

    public function findByLogin($login, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return CompteLoginView::getInstance()->findOneCompteByLogin($login, $hydrate);
    }

    public static function triAlphaCompte($a, $b){
        $a_data = $a->getData();
        $a_val_cmp = ($a_data['doc']['compte_type'] == 'INTERLOCUTEUR')? $a_data['doc']['nom'].' '.$a_data['doc']['prenom'] : $a_data['doc']['societe_informations']['raison_sociale'];

        $b_data = $b->getData();
        $b_val_cmp = ($b_data['doc']['compte_type'] == 'INTERLOCUTEUR')? $b_data['doc']['nom'].' '.$b_data['doc']['prenom'] : $b_data['doc']['societe_informations']['raison_sociale'];
        return strcmp($a_val_cmp,$b_val_cmp);
    }

    public static function sortGroupes($a, $b) {
      if(is_array($a) && is_array($b)){
        return strcmp($a['nom'], $b['nom']);
      }
      return strcmp($a->id, $b->id);
    }

    public function calculCoordonnees($adresse, $commune, $code_postal, $type = "housenumber") {
        if (!$adresse) {
		$adresse = '';
	}
        $adresse = trim(preg_replace("/B[\.]*P[\.]* [0-9]+/", "", $adresse));
        $url = CompteClient::API_ADRESSE_URL.'?q='.urlencode($adresse." ".$commune."&postcode=".$code_postal."&type=".$type);

        $file = file_get_contents($url, false, stream_context_create(["http"=>["timeout"=>1]]));
        $result = json_decode($file);
        if((!$result || !count($result->features)) && $type == "housenumber"){

            return $this->calculCoordonnees($adresse, $commune, $code_postal, "street");
        } elseif(!$result || !count($result->features)) {

            return false;
        }

        return array("lat" => $result->features[0]->geometry->coordinates[1], "lon" => $result->features[0]->geometry->coordinates[0]);
    }

    public function deleteLdapCompte($identifiant){
        $ldap = new CompteLdap();
        if (sfConfig::get('app_ldap_autogroup', false)) {
            $groupldap = new CompteGroupLdap();
            $ldapUid = $identifiant;
            foreach ($groupldap->getMembership($ldapUid) as $group) {
                $groupldap->removeMember($group, $ldapUid);
            }
        }
        $ldap->deleteCompte($identifiant, $verbose);
    }
}
