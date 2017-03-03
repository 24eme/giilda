<?php
class StatFilters
{
	protected $filters;
	protected $operator;
	
	const OPERATOR_AND = 'AND';
	const OPERATOR_OR = 'OR';
	
	public function __construct($operator, array $filters = array())
	{
		$this->operator = $operator;
		$this->filters = array();
		foreach ($filters as $filters) {
			$filter = current($filters);
			$ind = key($filters);
			$this->addFilter($ind, $filter);
		}
	}
	
	public function addFilter($ind, StatFilter $filter)
	{
		$this->filters[] = array($ind => $filter);
	}
	
	public function filter(array $datas)
	{
		$this->checkingFilters();
		$filteredDatas = array();
		$nbFilters = count($this->filters);
		foreach ($datas as $data) {
			$matches = 0;
			foreach ($this->filters as $filters) {
				$filter = current($filters);
				$ind = key($filters);
				if ($filter->match($data[$ind])) {
					$matches++;
				}
			}
			if (($this->operator == self::OPERATOR_AND && $matches == $nbFilters) || ($this->operator == self::OPERATOR_OR && $matches > 0)) {
				$filteredDatas[] = $data;
			}
		}
		return $filteredDatas;
	}
	
	protected function checkingFilters()
	{
		if (!in_array($this->operator, array(self::OPERATOR_AND, self::OPERATOR_OR))) {
			throw new Exception('Operator not allowed');
		}
		foreach ($this->filters as $filters) {
			$filter = current($filters);
			if (!($filter instanceof StatFilter)) {
				throw new Exception('Filter not allowed');
			}
		}
	}
}