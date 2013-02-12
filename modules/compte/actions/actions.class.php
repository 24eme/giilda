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
    
    protected function processFormCompte(sfWebRequest $request) {
        $this->compteForm = new CompteForm($this->compte);
        if (!$request->isMethod(sfWebRequest::POST)) {
          return;
        }

        $this->compteForm->bind($request->getParameter($this->compteForm->getName()));
        
        if (!$this->compteForm->isValid()) {
          return;
        }
        
        $this->compteForm->save();
                
        if (!$this->compte->isSameCoordonneeThanSociete()) {
                  
            return $this->redirect('compte_coordonnee_modification', $this->compte);
        }

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
      $this->getResponse()->setContentType('text/csv');
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
      $this->contacts_all = $request->getParameter('contacts_all');
    }
}
