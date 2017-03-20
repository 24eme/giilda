<?php

/**
 * Description of class AlerteConsultationSearch
 * @author jb
 */
class AlerteConsultationSearch {

    const ELASTICSEARCH_INDEX = 'ALERTE';
    const ELASTICSEARCH_LIMIT = 20;

    protected $values;
    protected $index;
    protected $nbResult;

    public function __construct($values = null) {
        $this->values = $values;
        $this->index = acElasticaManager::getType(self::ELASTICSEARCH_INDEX);
        $this->nbResult = 0;
    }

    public function getValues() {
        return $this->values;
    }

    public function setValues($values) {
        $this->values = $values;
    }

    public function getNbResult() {
        return $this->nbResult;
    }

    public function setNbResult($nbResult) {
        $this->nbResult = $nbResult;
    }

    public function getLimit($limit = null) {
        if (!$limit) {
            $limit = self::ELASTICSEARCH_LIMIT;
        }
        return $limit;
    }

    public function getElasticSearchDefaultResult($from = 0, $limit = null) {
        return $this->executeQuery("*", $from, $limit);
    }

    public function getElasticSearchResult($from = 0, $limit = null) {
        $filterStr = $this->getFiltersString();
        return $this->executeQuery($filterStr, $from, $limit);

    }

    protected function executeQuery($query, $from = 0, $limit = null) {
      $index = acElasticaManager::getType(self::ELASTICSEARCH_INDEX);
      $elasticaQueryString = new acElasticaQueryQueryString($query);
      $elasticaQuery = new acElasticaQuery();
      $elasticaQuery->setQuery($elasticaQueryString);
      $elasticaQuery->setFrom($from);
      $elasticaQuery->setLimit($limit);
      $search = $this->index->search($elasticaQuery);
      $this->setNbResult($search->getTotalHits());
      return $search->getResults();
    }


    protected function getCampagneCourante() {
        $year = date('Y');
        return $year . '-' . ($year + 1);
    }

	protected function getFiltersString()
	{
      $result = "";
      if(is_null($this->values) || !count($this->values)){
        $result = "*";
      }else{
        $cpt = 0;
        foreach ($this->values as $node => $value) {
          if($cpt > 0){  $result .= " , "; }
          $result .= 'doc.'.$node.":".$value;
          $cpt++;
        }
      }
      return $result;
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
