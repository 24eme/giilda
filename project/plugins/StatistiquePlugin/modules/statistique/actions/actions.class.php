<?php

/**
 * statistique actions.
 *
 * @package    declarvin
 * @subpackage statistique
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statistiqueActions extends sfActions {

    
	public function executeIndex(sfWebRequest $request) {
		
	}
	
	protected function getFields($key, $value)
	{
		if (!isset($value['properties'])) {
			return array($key => $key);
		}
		$result = array();
		foreach ($value['properties'] as $subkey => $subvalue) {
			$result = array_merge($result, $this->getFields($key.'.'.$subkey, $subvalue));
		}
		return $result;
	}

    public function executeDrmStatistiques(sfWebRequest $request) {
        $this->page = $request->getParameter('p', 1);
        $this->statistiquesConfig = sfConfig::get('app_statistiques_drm');
        if (!$this->statistiquesConfig) {
            throw new sfException('No configuration set for elasticsearch type drm');
        }
        
        $client = acElasticaManager::initializeClient($this->statistiquesConfig['elasticsearch_host'], $this->statistiquesConfig['elasticsearch_dbname']);
        $index = $client->getDefaultIndex()->getType($this->statistiquesConfig['elasticsearch_type']);
        
        $this->fields = array();
        $mapping = $index->getMapping()[$this->statistiquesConfig['elasticsearch_dbname']]['mappings'][$this->statistiquesConfig['elasticsearch_type']]['properties']['doc']['properties'];

        foreach ($mapping as $propertie => $value) {
        	$key = 'doc.'.$propertie;
        	$this->fields = array_merge($this->fields, $this->getFields($key, $value));
        }

        $this->form = new StatistiqueDRMFilterForm($this->fields);
        $this->query = $this->form->getDefaultQuery();
        $this->collapseIn = false;
        $this->filters = array();
        
        if ($request->hasParameter($this->form->getName())) {
        	$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
        		if ($q = $this->form->getQuery()) {
        			$this->query = $q;
        			$this->collapseIn = $this->form->getCollapseIn();
        			$this->filters = $this->form->getParameters();
        		}
        	}
        }
        
        $elasticaQuery = new acElasticaQuery();
        $elasticaQuery->setQuery($this->query);
        $elasticaQuery->setLimit($this->statistiquesConfig['nb_resultat']);
        $elasticaQuery->setFrom(($this->page - 1) * $this->statistiquesConfig['nb_resultat']);
        $result = $index->search($elasticaQuery);
        $this->hits = $result->getResults();
        $this->nbHits = $result->getTotalHits();
        $this->nbPage = ceil($this->nbHits / $this->statistiquesConfig['nb_resultat']);
    }

    public function executeVracStatistiques(sfWebRequest $request) {
        $this->page = $request->getParameter('p', 1);
        $this->statistiquesConfig = sfConfig::get('app_statistiques_vrac');
        if (!$this->statistiquesConfig) {
            throw new sfException('No configuration set for elasticsearch type vrac');
        }

        $client = acElasticaManager::initializeClient($this->statistiquesConfig['elasticsearch_host'], $this->statistiquesConfig['elasticsearch_dbname']);
        $index = $client->getDefaultIndex()->getType($this->statistiquesConfig['elasticsearch_type']);
        
        $this->fields = array();
        $mapping = $index->getMapping()[$this->statistiquesConfig['elasticsearch_dbname']]['mappings'][$this->statistiquesConfig['elasticsearch_type']]['properties']['doc']['properties'];
        foreach ($mapping as $propertie => $value) {
        	$key = 'doc.'.$propertie;
        	$this->fields = array_merge($this->fields, $this->getFields($key, $value));
        }
        
        $this->form = new StatistiqueVracFilterForm($this->fields);
        $this->query = $this->form->getDefaultQuery();
        $this->collapseIn = false;
        $this->filters = array();
        
        if ($request->hasParameter($this->form->getName())) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                if ($q = $this->form->getQuery()) {
                    $this->query = $q;
        			$this->collapseIn = $this->form->getCollapseIn();
        			$this->filters = $this->form->getParameters();
                }
            }
        }
        
        $elasticaQuery = new acElasticaQuery();
        $elasticaQuery->setQuery($this->query);


        $elasticaQuery->setLimit($this->statistiquesConfig['nb_resultat']);
        $elasticaQuery->setFrom(($this->page - 1) * $this->statistiquesConfig['nb_resultat']);
        $result = $index->search($elasticaQuery);
        $this->hits = $result->getResults();
        $this->nbHits = $result->getTotalHits();
        $this->nbPage = ceil($this->nbHits / $this->statistiquesConfig['nb_resultat']);
    }

}
