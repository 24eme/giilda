<?php

class compteActions extends sfActions
{
    
    public function executeAjout(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->compte = CompteClient::getInstance()->createCompteFromSociete($this->societe);
        $this->processFormCompte($request);        
        $this->setTemplate('modification');
    }

    public function executeModification(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();        
        $this->societe = $this->compte->getSociete(); 
        $this->processFormCompte($request);
    }
    
    public function executeModificationCompteEtablissement(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();        
        $this->societe = $this->compte->getSociete(); 
        $this->compteForm = new CompteModificationForm($this->compte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->compteForm->bind($request->getParameter($this->compteForm->getName()));
            if ($this->compteForm->isValid()) {
                if($this->compte->isNew()){
                    $this->compte->setStatut(EtablissementClient::STATUT_ACTIF);
                }
                $this->compteForm->save();
                $this->redirect('societe_visualisation',array('identifiant' => $this->societe->identifiant));
            }
        }
    }
    
    
    
    protected function processFormCompte(sfWebRequest $request) {
        $this->compteForm = new CompteExtendedModificationForm($this->compte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->compteForm->bind($request->getParameter($this->compteForm->getName()));
            if ($this->compteForm->isValid()) {
                $this->compteForm->save();
                $this->redirect('compte_visualisation',array('identifiant' => $this->compte->identifiant));
            }
        }
    }
 
    public function executeVisualisation(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();
        $this->societe = $this->compte->getSociete();
        if($this->compte->isSocieteContact())
            $this->redirect('societe_visualisation',array('identifiant' => $this->societe->identifiant));
        if($this->compte->isEtablissementContact())
            $this->redirect('etablissement_visualisation',array('identifiant' => preg_replace ('/^ETABLISSEMENT-/', '', $this->compte->getEtablissementOrigine())));
    }    

    private function initSearch(sfWebRequest $request) {
      $query = $request->getParameter('q', '*');
      if (! $request->getParameter('contacts_all') ) {
	$query .= " statut:ACTIF";
      }
      $this->selected_tags = array_unique(array_diff(explode(',', $request->getParameter('tags')), array('')));
      foreach ($this->selected_tags as $t) {
	$query .= ' tags.manuel:'.$t;
      }
      $qs = new acElasticaQueryQueryString($query);
      $q = new acElasticaQuery();
      $q->setQuery($qs);
      $this->contacts_all = $request->getParameter('contacts_all');
      $this->q = $request->getParameter('q');
      $this->args = array('q' => $this->q, 'contacts_all' => $this->contacts_all, 'tags' => implode(',', $this->selected_tags));
      return $q;
    }

    public function executeSearchcsv(sfWebRequest $request) {
      $index = acElasticaManager::getType('Compte');
      $q = $this->initSearch($request);
      $q->setLimit(1000000);
      $resset = $index->search($q);
      $this->results = $resset->getResults();
      $this->setLayout(false);
      $this->getResponse()->setContentType('text/csv');
    }

    public function executeAddtag(sfWebRequest $request) {
      $q = $this->initSearch($request);
      $q->setLimit(1000000);
      $index = acElasticaManager::getType('Compte');
      $resset = $index->search($q);
      $tag = $request->getParameter('tag');
      if (!$tag) {
	throw new sfException("Un tag doit être fourni pour pouvoir être ajouté");
      }
      foreach ($resset->getResults() as $res) {
	$data = $res->getData();
	$myCompte = CompteClient::getInstance()->findByIdentifiant($data['identifiant']);
	if (!$myCompte) {
	  continue;
	  throw new sfException($data['identifiant'].' ne correspond à aucun compte :(');
	}
	$myCompte->addTag('manuel', $tag);
	$myCompte->save();
      }
      return $this->redirect('compte_search', $this->args);
    }

    public function executeSearch(sfWebRequest $request) {
      $res_by_page = 50;
      $page = $request->getParameter('page', 1);
      $from = $res_by_page * ($page - 1);

      $q = $this->initSearch($request);
      $q->setLimit($res_by_page);
      $q->setFrom($from);
      $elasticaFacet 	= new acElasticaFacetTerms('tags');
      $elasticaFacet->setField('tags.manuel');
      $elasticaFacet->setSize(10);
      $elasticaFacet->setOrder('count');
      $q->addFacet($elasticaFacet);

      $index = acElasticaManager::getType('Compte');
      $resset = $index->search($q);
      $this->results = $resset->getResults();

      $this->nb_results = $resset->getTotalHits();
      $facets = $resset->getFacets();
      $this->facets = $facets['tags']['terms'];
      $this->last_page = ceil($this->nb_results / $res_by_page); 
      $this->current_page = $page;
    }
}
