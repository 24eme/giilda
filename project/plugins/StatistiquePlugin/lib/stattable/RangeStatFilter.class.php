<?php
class RangeStatFilter extends StatFilter
{
	protected $gte;
	protected $gt;
	protected $lte;
	protected $lt;
	
	const RANGE_GTE = 'gte';
	const RANGE_GT = 'gt';
	const RANGE_LTE = 'lte';
	const RANGE_LT = 'lt';
	
	public function __construct(array $operators = array())
	{
		foreach ($operators as $operator => $value) {
			$this->addOperator($operator, $value);
		}
	}
	
	public function addOperator($operator, $value) {
		if (!in_array($operator, array(self::RANGE_GTE, self::RANGE_GT, self::RANGE_LTE, self::RANGE_LT))) {
			throw new Exception ('Operator not allowes');
		}
		switch ($operator) {
			case self::RANGE_GTE:
				$this->gte = $value;
				break;
			case self::RANGE_GT:
				$this->gt = $value;
				break;
			case self::RANGE_LTE:
				$this->lte = $value;
				break;
			case self::RANGE_LT:
				$this->lt = $value;
				break;
		}
	}
	
	protected function getNbConditions()
	{
		$conditions = 0;
		if ($this->gte) {
			$conditions++;
		}
		if ($this->gt) {
			$conditions++;
		}
		if ($this->lte) {
			$conditions++;
		}
		if ($this->lt ) {
			$conditions++;
		}
		return $conditions;
	}
	
	public function match($value)
	{
		$conditions = $this->getNbConditions();
		$match = 0;
		if ($this->gte && $value >= $this->gte) {
			$match++;
		}
		if ($this->gt && $value > $this->gt) {
			$match++;
		}
		if ($this->lte && $value <= $this->lte) {
			$match++;				
		}
		if ($this->lt && $value < $this->lt) {
			$match++;				
		}
		return ($conditions == $match);
	}
	
}