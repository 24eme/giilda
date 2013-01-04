<?php

class compteActions extends sfActions
{
    
    public function executeAjout(sfWebRequest $request) {
        $this->societe = $this->getRoute()->getSociete();
        $this->compte = CompteClient::getInstance()->createCompte($this->societe);
        $this->processFormCompte($request);        
        $this->setTemplate('modification');
    }

    public function executeModification(sfWebRequest $request) {
        $this->compte = $this->getRoute()->getCompte();        
        $this->societe = $this->compte->getSociete(); 
        $this->processFormCompte($request);
    }
    
    protected function processFormCompte(sfWebRequest $request) {
        $this->compteForm = new CompteExtendedModificationForm($this->compte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->compteForm->bind($request->getParameter($this->compteForm->getName()));
            if ($this->compteForm->isValid()) {
                $this->compteForm->save();
                $this->redirect('societe_visualisation',array('identifiant' => $this->societe->identifiant));
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
      $qs = new acElasticaQueryQueryString($query);
      $q = new acElasticaQuery();
      $q->setQuery($qs);
      return $q;
    }

    public function executeSearchcsv(sfWebRequest $request) {
      $index = acElasticaManager::getType('Compte');
      $q = $this->initSearch($request);
      $q->setLimit(1000000);
      $resset = $index->search($q);
      $this->results = $resset->getResults();
      $this->setLayout(false);
      $this->getResponse()->setContentType('text/plain');
    }

    public function executeSearch(sfWebRequest $request) {
      $res_by_page = 50;
      $page = $request->getParameter('page', 1);
      $from = $res_by_page * ($page - 1);
      $this->q = $request->getParameter('q');

      $q = $this->initSearch($request);
      $q->setLimit($res_by_page);
      $q->setFrom($from);
      $index = acElasticaManager::getType('Compte');
      $resset = $index->search($q);

      $this->results = $resset->getResults();
      $this->nb_results = $resset->getTotalHits();
      $this->last_page = ceil($this->nb_results / $res_by_page); 
      $this->current_page = $page; 
    }
}
