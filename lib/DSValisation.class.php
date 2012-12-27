<?php 

class DSValidation {

    protected $ds = null;
    protected $errors = array();
    protected $warnings = array();

    public function __construct($ds, $options = null)
    {
        $this->ds = $ds;
        $this->check();
    }

    public function check() {
      $nbzeroounull = 0;
      foreach($this->ds->declarations as $key => $obj) {
	if (!$obj->stock_declare)
	  $nbzeroounull++;
      }
      if ($nbzeroounull) {
	$this->warnings['STOCK_ZERO_OU_NULL'] = 'Pas de stock déclaré pour '.$nbzeroounull.' produit(s)'; 
      }
      return $this->isValid();
    }

    public function getErrors() {

        return $this->errors; 
    }

    public function getWarnings() {

        return $this->warnings; 
    }

    public function isValid() {

        return count($this->errors) == 0;
    }
}