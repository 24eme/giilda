<?php

/**
 * Description of class AlerteConsultationSearch
 * @author jb
 */
class AlerteConsultationSearch 
{
	const ELASTICSEARCH_INDEX = 'alerte';
	const ELASTICSEARCH_LIMIT = 20;
	protected $values;
	protected $index;
	protected $nbResult;

    public function __construct($values = null) 
    {
    	$this->values = $values;
    	$this->index = acElasticaManager::getType(self::ELASTICSEARCH_INDEX);
    	$this->nbResult = 0;
    }
    
    public function getValues()
    {
    	return $this->values;
    }
    
    public function setValues($values)
    {
    	$this->values = $values;
    }
    
    public function getNbResult()
    {
    	return $this->nbResult;
    }
    
    public function setNbResult($nbResult)
    {
    	$this->nbResult = $nbResult;
    }    
    
    public function getLimit($limit = null)
    {
    	if (!$limit) {
    		$limit = self::ELASTICSEARCH_LIMIT;
    	}
    	return $limit;
    }
    
    public function getElasticSearchDefaultResult($from = 0, $limit = null)
    {
    	$query = $this->makeQuery(new acElasticaQueryMatchAll(), $from, $limit);
      	return $this->getResult($query);
    }
       
    public function getElasticSearchResult($from = 0, $limit = null)
    {
        $query = $this->makeQuery(new acElasticaFiltered(new acElasticaQueryMatchAll(), $this->getFilters()), $from, $limit);
      	return $this->getResult($query);
    }
    
    protected function makeQuery($query, $from = 0, $limit = null)
    {
    	$limit = $this->getLimit($limit);
    	$elasticaQuery = new acElasticaQuery();   
    	$elasticaQuery->setQuery($query);
        $elasticaQuery->setFrom($from);
        $elasticaQuery->setsort(array("date_dernier_statut" => array("order" => "desc")));
        $elasticaQuery->setLimit($limit);
        
      	return $elasticaQuery;
    }
    
    protected function getResult($query)
    {
      	$search = $this->index->search($query);
      	$this->setNbResult($search->getTotalHits());
    	return $search->getResults();
    }
    
    protected function getCampagneCourante()
    {
    	$year = date('Y');
    	return $year.'-'.($year+1);    	
    }
    
	protected function getFilters()
	{
		$and_filter = new acElasticaFilterAnd();
		foreach ($this->values as $node => $value) {
			$result = $this->buildQueryString($node, $value);
			if ($result) {
				$and_filter->addFilter($result);
			}
		}
		return $and_filter;
	}
	
	protected function buildQueryString($node, $value)
	{
		$string = ($value !== null && $value !== '')? $node.':'.$value : null;
		if (!$string) {
			return null;
		}
		$query_string = new acElasticaQueryQueryString($string);
		$query_filter = new acElasticaFilterQuery($query_string);
		return $query_filter;
	}

}

