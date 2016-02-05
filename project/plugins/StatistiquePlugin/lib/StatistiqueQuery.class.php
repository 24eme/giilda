<?php
class StatistiqueQuery
{
	const CONFIG_QUERY_STRING = 'query_string';
	const CONFIG_QUERY_STRING_OR_PRODUCT = 'query_string_or_product';
	const CONFIG_QUERY_STRING_OR = 'query_string_or';
	const CONFIG_QUERY_RANGE = 'query_range';
	const CONFIG_QUERY_STRING_OR_PRODUCT_VRAC = 'query_string_or_product_vrac';
	const CONFIG_QUERY_EXISTS = 'query_exists';
	const CONFIG_QUERY_MISSING = 'query_missing';
	
	protected $fieldsConfig;
	protected $values;
	
	public function __construct(array $fieldsConfig, array $values)
	{
		$this->fieldsConfig = $fieldsConfig;
		$this->values = $values;
	}
	
	public function getFilter()
	{
		$and_filter = new acElasticaFilterAnd();
		foreach ($this->values as $node => $value) {
			if (!isset($this->fieldsConfig[$node])) {
				throw new sfException('No config for node '.$node);
			}
			switch ($this->fieldsConfig[$node]) {
				case self::CONFIG_QUERY_STRING :
					$result = $this->buildQueryString($node, $value);
					break;
				case self::CONFIG_QUERY_STRING_OR_PRODUCT :
					$result = $this->buildQueryStringOrProduct($node, $value);
					break;
				case self::CONFIG_QUERY_STRING_OR_PRODUCT_VRAC :
					$result = $this->buildQueryStringOrProductVrac($node, $value);
					break;
				case self::CONFIG_QUERY_STRING_OR :
					$result = $this->buildQueryStringOr($node, $value);
					break;
				case self::CONFIG_QUERY_RANGE :
					$result = $this->buildQueryRange($node, $value);
					break;
				case self::CONFIG_QUERY_EXISTS :
					$result = $this->buildQueryExists($node);
					break;
				case self::CONFIG_QUERY_MISSING :
					$result = $this->buildQueryMissing($node);
					break;
    			default:
    				$result = null;
    				break;
			}
			if ($result) {
				$and_filter->addFilter($result);
			}
		}
		return $and_filter;
	}
	
	private function buildQueryString($node, $value)
	{
		$string = ($value !== null && $value !== '')? $node.':'.$value : null;
		if (!$string) {
			return null;
		}
		$query_string = new acElasticaQueryQueryString($string);
		$query_filter = new acElasticaFilterQuery($query_string);
		return $query_filter;
	}
	
	private function buildQueryStringOrProduct($node, $values)
	{
		$string = '';
		$valuesForNode = array();
		$this->getValues($valuesForNode, $values);
		foreach ($valuesForNode as $valueForNode) {
			if ($string && $valueForNode !== null && $valueForNode !== '') {
				$string .= ' OR ';
			}
			$valueForNode = str_replace('/declaration/', '', $valueForNode);
			$string .= ($valueForNode !== null && $valueForNode !== '')? $node.'.'.str_replace('/', '.', $valueForNode).'.selecteur:1' : null;
		}
		if (!$string) {
			return null;
		}
		$query_string = new acElasticaQueryQueryString($string, 'OR');
		$query_filter = new acElasticaFilterQuery($query_string);
		return $query_filter;
	}
	
	private function buildQueryStringOrProductVrac($node, $values)
	{
		$string = '';
		$valuesForNode = array();
		$this->getValues($valuesForNode, $values);
		foreach ($valuesForNode as $valueForNode) {
			if ($string && $valueForNode !== null && $valueForNode !== '') {
				$string .= ' OR ';
			}
			$string .= ($valueForNode !== null && $valueForNode !== '')? $node.':('.str_replace('/', ' ', $valueForNode).')' : null;
		}
		if (!$string) {
			return null;
		}
		$query_string = new acElasticaQueryQueryString($string, 'AND');
		$query_filter = new acElasticaFilterQuery($query_string);
		return $query_filter;
	}
	
	private function buildQueryStringOr($node, $values)
	{
		$string = '';
		$valuesForNode = array();
		$this->getValues($valuesForNode, $values);
		foreach ($valuesForNode as $valueForNode) {
			if ($string && $valueForNode !== null && $valueForNode !== '') {
				$string .= ' OR ';
			}
			$string .= ($valueForNode !== null && $valueForNode !== '')? $node.':'.$valueForNode : null;
		}
		if (!$string) {
			return null;
		}
		$query_string = new acElasticaQueryQueryString($string, 'OR');
		$query_filter = new acElasticaFilterQuery($query_string);
		return $query_filter;
	}
	
	private function buildQueryRange($node, $values)
	{
		
		if (!isset($values['from']) && !isset($values['to'])) {
			return null;
		}
		if (!$values['from'] && !$values['to']) {
			return null;
		}
		$query_filter_range = new acElasticaFilterRange($node, $values);
		return $query_filter_range;
	}
	
	private function buildQueryExists($node)
	{
		$query_exists = new acElasticaFilterExists($node);
		return $query_exists;
	}
	
	private function buildQueryMissing($node)
	{
		$query_missing = new acElasticaFilterMissing($node);
		return $query_missing;
	}
	
	private function getValues(&$result, $values)
	{
		if (is_array($values)) {
			foreach ($values as $value) {
				$this->getValues($result, $value);
			}
		} else {
			$result[] = $values;
		}
	}
}