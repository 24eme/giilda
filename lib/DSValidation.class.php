<?php 

class DSValidation extends DocumentValidation
{
  public function configure() {
    $this->addControle('vigilance', 'stock_zero_ou_null', 'Il reste des stocks non saisis');
  }

  public function controle()
  {

    $nbzeroounull = 0;
    foreach($this->document->declarations as $key => $obj) {
      if (!$obj->stock_declare)
	$nbzeroounull++;
    }
    if ($nbzeroounull) {
      $this->addPoint('vigilance', 'stock_zero_ou_null', $nbzeroounull.' produit(s) concerné(s)', $this->generateUrl('ds_edition_operateur', $this->document)); 
    }
  }

}