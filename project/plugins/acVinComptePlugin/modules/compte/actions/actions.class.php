<?php

class compteActions extends sfCredentialActions {

    public function executeAjout(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->compte = CompteClient::getInstance()->createCompteInterlocuteurFromSociete($this->societe);
        $this->applyRights();
        if(!$this->modification && !$this->reduct_rights){

          return $this->forward('acVinCompte','forbidden');
        }
        $this->processFormCompte($request);
        $this->setTemplate('modification');
    }

    public function executeModification(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();
        $this->societe = $this->compte->getSociete();
        $this->applyRights();
        if(!$this->modification && !$this->reduct_rights){

          return $this->forward('acVinCompte','forbidden');
        }
        $this->processFormCompte($request);
    }

    protected function processFormCompte(sfWebRequest $request) {
        $this->compteForm = new InterlocuteurForm($this->compte);
        if (!$request->isMethod(sfWebRequest::POST)) {
          return;
        }

        $this->compteForm->bind($request->getParameter($this->compteForm->getName()));

        if (!$this->compteForm->isValid()) {
          return;
        }

        $this->compteForm->save();
        return $this->redirect('compte_visualisation', $this->compte);
    }

    public function executeModificationCoordonnee(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();
        $this->societe = $this->compte->getSociete();
        $this->compteForm = new CompteCoordonneeForm($this->compte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->compteForm->bind($request->getParameter($this->compteForm->getName()));
            if ($this->compteForm->isValid()) {
                if($this->compte->isNew()){
                    $this->compte->setStatut(EtablissementClient::STATUT_ACTIF);
                }
                $this->compteForm->save();
                $this->redirect('compte_visualisation', $this->compte);
            }
        }
    }

    public function executeVisualisation(sfWebRequest $request) {
        if(!SocieteConfiguration::getInstance()->isVisualisationTeledeclaration() && !$this->getUser()->hasCredential(myUser::CREDENTIAL_CONTACT) && !$this->getUser()->isStalker()) {

            throw new sfError403Exception();
        }

        $this->compte = $this->getRoute()->getCompte();
        $this->societe = $this->compte->getSociete();
        $this->formAjoutGroupe = new CompteGroupeAjoutForm('INTERPRO-declaration');
        $this->applyRights();
        if(!$this->compte->lat && !$this->compte->lon){
          $this->compte->updateCoordonneesLongLat();
          $this->compte->save();
        }
        if($this->compte->isEtablissementContact()) {
            return $this->redirect('etablissement_visualisation', $this->compte->getEtablissement());
        }
        if($this->compte->isSocieteContact()) {
            return $this->redirect('societe_visualisation',array('identifiant' => $this->societe->identifiant));
        }
        $this->modifiable = $this->getUser()->hasCredential('contacts');
    }

    public function executeSwitchStatus(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();
        $newStatus = "";
        if($this->compte->isActif()){
           $newStatus = CompteClient::STATUT_SUSPENDU;
        }
        if($this->compte->isSuspendu()){
           $newStatus = CompteClient::STATUT_ACTIF;
        }

        $this->compte->setStatut($newStatus);
        $this->compte->save();
        return $this->redirect('compte_visualisation', array('identifiant' => $this->compte->identifiant));
    }

    public function executeSwitchAlerte(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();
        $newStatus = null;
        $this->compte->add('en_alerte', !($this->compte->exist('en_alerte') && $this->compte->en_alerte));
        $this->compte->save();
        return $this->redirect('compte_visualisation', array('identifiant' => $this->compte->identifiant));
    }



    public function executeInterlocuteurDelete(sfWebRequest $request) {
        $compte = $this->getRoute()->getCompte();
        if($compte->compte_type != CompteClient::TYPE_COMPTE_INTERLOCUTEUR){
            throw new sfException("Le compte d'identifiant ".$compte->identifiant." ne peux pas être supprimer ce n'est pas un compte Interlocuteur");
        }
        $societe = $compte->getSociete();

        $societe->contacts->remove($compte->_id);
        $societe->save();
        $compte->delete();
        return $this->redirect('societe_visualisation', array('identifiant' => $societe->identifiant));
    }



    private function initSearch(sfWebRequest $request, $extratag = null, $excludeextratag = false) {
      $query = $request->getParameter('q', '*');
      $this->notfacets = array();
      $this->hasFilters = false;
      if($query == ""){
        $query.="*";
      }
      if (trim($query, "* ") != "") {
          $this->hasFilters = true;
      }
      if (! $request->getParameter('contacts_all') ) {
		      $query .= " doc.statut:ACTIF";
      }
      $this->selected_rawtags = array_unique(array_diff(explode(',', $request->getParameter('tags')), array('')));
      $this->selected_typetags = array();
      $this->selected_nottypetags = array();
      if (count($this->selected_rawtags) > 0) {
          $this->hasFilters = true;
      }
      foreach ($this->selected_rawtags as $t) {
		if (preg_match('/^(\!?)([^:]+):(.+)$/', $t, $m)) {
	  		if (!isset($this->selected_typetags[$m[2]])) {
	    		$this->selected_typetags[$m[2]] = array();
	  		}
	  		$this->selected_typetags[$m[2]][] = $m[1].$m[3];
            if ($m[1]) {
                $query .=  ' NOT(';
                $this->selected_nottypetags[$m[2]][] = $m[3];
            }
            $t = $m[2].':'.$m[3];
		}
		$query .= ' doc.tags.'.$t;
        if ($m[1]) {
            $query .= ') ';
        }
      }
      $this->real_q = $query;
      if ($extratag) {
		$query .= ($excludeextratag) ? ' -' : ' ';
		$query .= 'doc.tags.manuel:'.$extratag;
      }
      $qs = new acElasticaQueryQueryString($query);
      $q = new acElasticaQuery();

      $q->setQuery($qs);
      $this->contacts_all = $request->getParameter('contacts_all');
      $this->q = $request->getParameter('q');
      $this->args = array('q' => $this->q, 'contacts_all' => $this->contacts_all, 'tags' => implode(',', $this->selected_rawtags));
      return $q;
    }

    public function executeSearchcsv(sfWebRequest $request) {
        ini_set('memory_limit', '1G');
        $index = acElasticaManager::getType('COMPTE');
        $this->selected_rawtags = array_unique(array_diff(explode(',', $request->getParameter('tags')), array('')));
        $this->selected_typetags = array();
        foreach ($this->selected_rawtags as $t) {
              if (preg_match('/^([^:]+):(.+)$/', $t, $m)) {
          		if (!isset($this->selected_typetags[$m[1]])) {
            		$this->selected_typetags[$m[1]] = array();
          		}
          		$this->selected_typetags[$m[1]][] = $m[2];
        	}
        }

        $q = $this->initSearch($request);
        $resset = $index->search($q);
        $nbTotal = $resset->getTotalHits();
        if ($nbTotal > 1000000) {
        	throw new sfException('Trop de résultat ES pour l\'export CSV');
        }
        $q = $this->initSearch($request);
        $q->setSize($nbTotal);
        $resset = $index->search($q);
        $this->results = $resset->getResults();
        $this->logins = CompteLoginView::getInstance()->getAllLogins();
        $this->setLayout(false);
        $filename = 'export_contacts';
        $attachement = "attachment; filename=".$filename.".csv";
        $this->response->setContentType('text/csv');
        $this->response->setHttpHeader('Content-Disposition',$attachement);
    }

    private function addRemoveGroupe(sfWebRequest $request, $remove = false) {
      $compteAjout = $request->getParameter('compte_groupe_ajout');
      $groupe = $request->getParameter('groupeName');
      $retour = $request->getParameter('retour',null);
      $compteIdentifiant = str_replace("COMPTE-","",$compteAjout["id_compte"]);
      if($request->getParameter('identifiant',null)){
        $compteIdentifiant = $request->getParameter('identifiant');
      }

      $index = acElasticaManager::getType('COMPTE');
      $qs = new acElasticaQueryQueryString("* doc.tags.groupes:".Compte::transformTag($groupe)." doc.identifiant:".$compteIdentifiant);
      $q = new acElasticaQuery();
      $q->setQuery($qs);
      $resset = $index->search($q);
      $nbres = $resset->getTotalHits();
      $this->setTemplate('addremovetag');

      if (!$remove && !$nbres) {
        $this->restants = 1;
        return false;
      }
      if ($remove && $nbres) {
        $this->restants = 1;
        return false;
      }
      if($retour){
        return $this->redirect('compte_visualisation', array('identifiant' => str_replace("COMPTE-","",$compteIdentifiant)));
      }
      return true;
    }



    private function addremovetag(sfWebRequest $request, $remove = false) {
      $index = acElasticaManager::getType('COMPTE');
      $tag = Compte::transformTag($request->getParameter('tag'));
      $q = $this->initSearch($request, $tag, !$remove);

      //$q->setLimit(1000000);
      $resset = $index->search($q);

      if (!$tag) {
		throw new sfException("Un tag doit être fourni pour pouvoir être ajouté");
      }
      if ((!$this->real_q || !$this->hasFilters) && !$remove) {
		throw new sfException("Il n'est pas possible d'ajouter un tag sur l'ensemble des contacts");
      }
      $cpt = 0;
      $nbimpactables =  $resset->getTotalHits();
      foreach ($resset->getResults() as $res) {
	$data = $res->getData();
	$doc = CompteClient::getInstance()->findByIdentifiant($data['doc']['identifiant'], acCouchdbClient::HYDRATE_JSON);
	if (!$doc) {
	  continue;
	}
	if (!isset($doc->tags->manuel)) {
	  $doc->tags->manuel = array();
	}else{
	  $doc->tags->manuel = json_decode(json_encode($doc->tags->manuel), true);
	}
	if ($remove && $doc->tags->manuel) {
	  $doc->tags->manuel = array_values(array_diff($doc->tags->manuel, array($tag)));
	}else{
	  $doc->tags->manuel = array_unique(array_merge($doc->tags->manuel, array($tag)));
	}
	CompteClient::getInstance()->storeDoc($doc);
	$cpt++;
	if ($cpt > 200) {
	  break;
	}
      }
      if ($cpt == 1 && strpos($request->getParameter('retour'), '/visualisation')) {
          return true;
      }
      $q = $this->initSearch($request, $tag, !$remove);
      $resset = $index->search($q);

      $nbimpactes = $resset->getTotalHits();

      $this->setTemplate('addremovetag');
      if ($nbimpactes) {
	$this->restants = $nbimpactables;
	return false;
      }

      if (!$remove && $nbimpactes) {
	$this->restants = $nbimpactes;
	return false;
      }
      return true;
    }

    public function executeAddtag(sfWebRequest $request) {
      if (!$this->addremovetag($request, false)) {
		      return ;
      }

      if($request->getParameter('retour')) {

          return $this->redirect($request->getParameter('retour'));
      }

      return $this->redirect('compte_search', $this->args);
    }

    public function executeAddingroupe(sfWebRequest $request) {
      $identifiant = $request->getParameter('identifiant');
      $this->groupe = $request->getParameter('groupe');
      if(!$this->groupe){
        return $this->redirect('compte_visualisation', array('identifiant' => str_replace("COMPTE-","",$identifiant)));
      }
      $allGroupes = CompteClient::getInstance()->getAllTagsGroupes();
      foreach ($allGroupes as $grp) {
       if($grp->id == $this->groupe){
         $request->setParameter('groupeName',$grp->text);
         $this->groupeName = $request->getParameter('groupeName');
       }
      }
      $this->compte = CompteClient::getInstance()->find('COMPTE-'.$identifiant);
      $formRequest = $request->getParameter('compte_groupe_ajout');
      $formRequest['id_compte'] = 'COMPTE-'.$identifiant;
      $request->setParameter('compte_groupe_ajout',$formRequest);
      $this->form = new CompteGroupeAjoutForm('INTERPRO-declaration');
      if ($request->isMethod(sfWebRequest::POST)) {
          $this->form->bind($request->getParameter($this->form->getName()));
          if ($this->form->isValid()) {
              $values = $this->form->getValues();
              $compte = CompteClient::getInstance()->find($values['id_compte']);
              $compte->addInGroupes($this->groupeName,$values['fonction']);
              $compte->save();
              $request->setParameter('retour',true);
              if (!$this->addRemoveGroupe($request, false)) {
                  return $this->redirect('compte_visualisation', array('identifiant' => str_replace("COMPTE-","",$identifiant)));
              }
              return $this->redirect('compte_visualisation', array('identifiant' => str_replace("COMPTE-","",$identifiant)));
          }
      }
    }

    public function executeRemovetag(sfWebRequest $request) {
      if (!$this->addremovetag($request, true)) {
		      return ;
      }
      if ($r = $request->getParameter('retour')) {
          return $this->redirect($r);
      }
      $this->args['tags'] = implode(',', array_diff($this->selected_rawtags, array('manuel:'.$request->getParameter('tag'))));
      return $this->redirect('compte_search', $this->args);
    }

    public function executeGroupe(sfWebRequest $request){
      $request->setParameter('contacts_all',true);
      $index = acElasticaManager::getType('COMPTE');
      $this->groupeName = str_replace('!','.',$request->getParameter('groupeName'));
      $this->filtre = "groupes:".Compte::transformTag($this->groupeName);
      $request->addRequestParameters(array('tags' => $this->filtre));
      $q = $this->initSearch($request);
      $q->setLimit(4000);
		  $elasticaFacet 	= new acElasticaFacetTerms('groupes');
		  $elasticaFacet->setField('doc.tags.groupes');
		  $elasticaFacet->setSize(250);
		  $q->addFacet($elasticaFacet);

      $resset = $index->search($q);
      $this->results = $resset->getResults();
      uasort($this->results, 'CompteClient::triAlphaCompte');
      $this->form = new CompteGroupeAjoutForm('INTERPRO-declaration');
      if ($request->isMethod(sfWebRequest::POST)) {
          $this->form->bind($request->getParameter($this->form->getName()));
          if ($this->form->isValid()) {
              $values = $this->form->getValues();
              $compte = CompteClient::getInstance()->find($values['id_compte']);
              $compte->addInGroupes($this->groupeName,$values['fonction']);
              $compte->save();
              if (!$this->addRemoveGroupe($request, false)) {
                  return ;
              }
              $this->redirect('compte_groupe', array('groupeName' => str_replace('.','!',fOutputEscaper::unescape($this->groupeName))));
          }
      }
    }

    public function executeRemovegroupe(sfWebRequest $request) {
      $groupeName = str_replace('!','.',$request->getParameter('groupeName'));
      $identifiant = $request->getParameter('identifiant');
      $compte = CompteClient::getInstance()->findByIdentifiant($identifiant);
      $compte->removeGroupes($groupeName);
      $compte->save();
      $request->addRequestParameters(array('id_compte' => "COMPTE-".$identifiant));
      if (!$this->addRemoveGroupe($request, true)) {
                return ;
      }
      $this->redirect('compte_groupe', array('groupeName' => str_replace('.','!',sfOutputEscaper::unescape($groupeName))));
    }

    public function executeTags(sfWebRequest $request) {
      $q = new acElasticaQuery();
      $this->addTagFacetsToQuerry($q);
      $index = acElasticaManager::getType('COMPTE');
      $resset = $index->search($q);
      $this->facets = $resset->getFacets();
    }

    private function addTagFacetsToQuerry($q) {
      $facets = array('manuel' => 'doc.tags.manuel', 'export' => 'doc.tags.export', 'produit' => 'doc.tags.produit', 'statuts' => 'doc.tags.statuts', 'activite' => 'doc.tags.activite', 'groupes' => 'doc.tags.groupes', 'automatique' => 'doc.tags.automatique','relations' => 'doc.tags.relations', 'documents' => 'doc.tags.documents', 'droits' => 'doc.tags.droits');
      foreach($facets as $nom => $f) {
        $elasticaFacet 	= new acElasticaFacetTerms($nom);
        $elasticaFacet->setField($f);
        $elasticaFacet->setSize(150);
        $q->addFacet($elasticaFacet);
      }
    }

    public function executeGroupes(sfWebRequest $request){
      $q = new acElasticaQuery();
      $elasticaFacet   = new acElasticaFacetTerms('groupes');
      $elasticaFacet->setField('doc.groupes.nom');
      $elasticaFacet->setSize(250);
      $q->addFacet($elasticaFacet);
      $index = acElasticaManager::getType('COMPTE');
      $resset = $index->search($q);
      $this->facets = $resset->getFacets();

      $this->form = new CompteNewGroupeForm();
      if ($request->isMethod(sfWebRequest::POST)) {
          $this->form->bind($request->getParameter($this->form->getName()));
          if ($this->form->isValid()) {
            $values = $this->form->getValues();
            $this->groupeName = $values['nom_groupe'];
            $this->redirect('compte_groupe', array('groupeName' => str_replace('.','!',$this->groupeName)));
          }
      }
    }


    public function executeSearch(sfWebRequest $request) {
      $res_by_page = 30;
      $page = $request->getParameter('page', 1);
      $from = $res_by_page * ($page - 1);

      $this->contacts_all = $request->getParameter('contacts_all');

      $q = $this->initSearch($request);
      $q->setLimit($res_by_page);
      $q->setFrom($from);
      $this->addTagFacetsToQuerry($q);
      try {
          $index = acElasticaManager::getType('COMPTE');
          $resset = $index->search($q);
          $this->results = $resset->getResults();
          $this->nb_results = $resset->getTotalHits();
          $this->facets = $resset->getFacets();
          foreach($this->selected_nottypetags as $type => $nottags) {
                foreach($nottags as $nottag) {
                    $this->facets[$type]['buckets'][] = array('key' => $nottag, 'doc_count' => -1);
                }
          }
      }catch(Exception $e) {
          $this->results = array();
          $this->nb_results = 0;
          $this->facets = array();
      }


      ksort($this->facets);

      $this->last_page = ceil($this->nb_results / $res_by_page);
      $this->current_page = $page;
    }

    public function executeSearchadvanced(sfWebRequest $request) {
    	$this->form = new CompteRechercheAvanceeForm();

    	if (!$request->isMethod(sfWebRequest::POST)) {

    		return sfView::SUCCESS;
    	}

    	$this->form->bind($request->getParameter($this->form->getName()));

    	if (!$this->form->isValid()) {

    		return sfView::SUCCESS;
    	}

    	$identifiants = explode("\n", preg_replace("/^\n/", "",  preg_replace("/\n$/", "", preg_replace("/([^0-9\n]+|\n\n)/", "", str_replace("\n", "\n", $this->form->getValue('identifiants'))))));

    	foreach($identifiants as $index => $identifiant) {
    		$identifiants[$index] = trim($identifiant);
    		if(!$identifiants[$index]) {
    			unset($identifiants[$index]);
    		}
    	}

    	return $this->redirect('compte_search', array("q" => "(doc.etablissement_informations.cvi:" . implode(" OR doc.etablissement_informations.cvi:", $identifiants) . ")", "contacts_all" => 1));
    }
}
