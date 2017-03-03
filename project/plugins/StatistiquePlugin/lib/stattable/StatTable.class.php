<?php
class StatTable
{
	protected $originDatas;
	protected $statFilters;
	protected $datas;
	protected $columnsHeader;
	protected $pivot;
	protected $columns;
	protected $aggregat;
	protected $pivotTotal;
	protected $fullPivotTotal;
	protected $columnTotal;
	
	const HASH_SEPARATOR = '_/_';
	const TOTAL_KEY = 'ZZTOTAUX';
	
	public function __construct(array $datas, $filters = null)
	{
		$this->originDatas = $datas;
		$this->datas = array();
		$this->statFilters = null;
		$this->columnsHeader = array();
		$this->fullPivotTotal = false;
		if ($filters) {
			$this->addFilters($filters);
		} else {
			$this->filter();
		}
	}
	
	public function addFilters(StatFilters $filters)
	{
		$this->statFilters = $filters;
		$this->filter();
	}
	
	protected function filter()
	{
		$this->datas = ($this->statFilters)? $this->statFilters->filter($this->originDatas) : $this->originDatas;
	}
	
	public function joinColumns(array $cols, $joinToken = null)
	{
		$nbCol = count(current($this->originDatas));
		foreach ($this->originDatas as $key => $datas) {
			foreach ($cols as $col) {
				if (isset($datas[$col])) {
					if (!$this->originDatas[$key][$nbCol-1]) {
						$this->originDatas[$key][$nbCol-1] = '';
					} else {
						$this->originDatas[$key][$nbCol-1] .= $joinToken;
					}
					$this->originDatas[$key][$nbCol-1] .= $this->originDatas[$key][$col];
				} else {
					throw new Exception('Indices doesn\'t exist');
				}
			}
			
		}
		$this->filter();
		return $nbCol-1;
	}
	
	public function getDatas()
	{
		return $this->datas;
	}
	
	public function pivotOn($colInd)
	{
		$this->pivot = (is_array($colInd))? $colInd : array($colInd);
	}
	
	public function columnsOn($colInd)
	{
		$this->columns = $colInd;
	}
	
	public function aggregatOn($colInd)
	{
		$this->aggregat = (!is_array($colInd))? array($colInd) : $colInd;
	}
	
	public function addTotalPivot($colInd = null)
	{
		if (!$colInd) {
			$pivotTotal = $this->pivot;
		} else {
			$pivotTotal = (is_array($colInd))? $colInd : array($colInd);
		}
		if ($pivotTotal == $this->pivot) {
			$this->fullPivotTotal = true;
		} else {
			$this->pivotTotal = $pivotTotal;
		}
		
	}
	
	public function addTotalColumn($bool = true)
	{
		$this->columnTotal = $bool;
	}
	
	protected function getPivotHash($items, $forTotal = false)
	{
		$hash = '';
		$pivots = ($forTotal)? $this->pivotTotal : $this->pivot; 
		foreach ($pivots as $pivot) {
			if ($hash) {
				$hash .= self::HASH_SEPARATOR;
			}
			$hash .= trim($items[$pivot]);
		}
		return (!$forTotal)? $hash : $hash.self::HASH_SEPARATOR.self::TOTAL_KEY;
	}
	
	protected function processValues($pivot, array $items, &$result, $makeColumnsHeader = false)
	{
		if (!isset($result[$pivot])) {
			$result[$pivot] = array();
		}
		if (!isset($result[$pivot][$items[$this->columns]])) {
			$result[$pivot][$items[$this->columns]] = array();
			if ($makeColumnsHeader) {
				if (!in_array($items[$this->columns], $this->columnsHeader)) {
					$this->columnsHeader[] = $items[$this->columns];
				}
			}
			foreach ($this->aggregat as $key) {
				$result[$pivot][$items[$this->columns]][$key]['nb'] = 0;
				$result[$pivot][$items[$this->columns]][$key]['sum'] = 0;
			}
		}
		if ($this->columnTotal && !isset($result[$pivot][self::TOTAL_KEY])) {
			$result[$pivot][self::TOTAL_KEY] = array();
			foreach ($this->aggregat as $key) {
				$result[$pivot][self::TOTAL_KEY][$key]['nb'] = 0;
				$result[$pivot][self::TOTAL_KEY][$key]['sum'] = 0;
			}
		}
		foreach ($this->aggregat as $key) {
				$val = ($items[$key] < 0)? ($items[$key] * -1) : $items[$key];
				$result[$pivot][$items[$this->columns]][$key]['nb'] += 1;
				$result[$pivot][$items[$this->columns]][$key]['sum'] += $val;
				if ($this->columnTotal) {
					$result[$pivot][self::TOTAL_KEY][$key]['nb'] += 1;
					$result[$pivot][self::TOTAL_KEY][$key]['sum'] += $val;
				}
		}
	}
	
	public function getStatTable($csvFormat = false, $headers = null, $pivotLibelles = array())
	{
		$result = array();
		foreach ($this->datas as $items) {
			// Values
			$pivot = $this->getPivotHash($items);
			$this->processValues($pivot, $items, $result, true);
			// Sub totaux
			$pivotTotal = ($this->pivotTotal)? $this->getPivotHash($items, true) : null;
			if ($pivotTotal) {
				$this->processValues($pivotTotal, $items, $result);
			}
			// Global totaux
			if ($this->fullPivotTotal) {
				$key = '';
				foreach ($this->pivot as $p) {
					if ($key) {
						$key .= self::HASH_SEPARATOR;
					}
					$key .= self::TOTAL_KEY;
				}
				$this->processValues($key, $items, $result);
			}
		}
		ksort($result, SORT_NATURAL | SORT_FLAG_CASE);
		return ($csvFormat)? $this->makeCsv($result, $headers, $pivotLibelles) : $result;
	}
	
	protected function makeCsv($tab, $headers = null, $pivotLibelles = array())
	{
		$csv = ($headers)? $headers."\n" : '';
		sort($this->columnsHeader);
		foreach ($tab as $pivot => $columns) {
			$csv .= str_replace(array_keys($pivotLibelles), array_values($pivotLibelles), str_replace(self::HASH_SEPARATOR, ';', $pivot));
			foreach ($this->columnsHeader as $columnHeader) {
				if (isset($columns[$columnHeader])) {
					foreach ($this->aggregat as $key) {
						$csv .= ';'.number_format($columns[$columnHeader][$key]['sum'], 2, ',', '');
					}
				} else {
					foreach ($this->aggregat as $key) {
						$csv .= ';';
					}
				}
			}
			if ($this->columnTotal) {
				foreach ($this->aggregat as $key) {
					$csv .= ';'.number_format($columns[self::TOTAL_KEY][$key]['sum'], 2, ',', '');
				}
			}
			$csv .= "\n";
		}
		return $csv;
	}
	
}