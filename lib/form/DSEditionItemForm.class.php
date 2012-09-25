<?php
class DSEditionItemForm extends acCouchdbObjectForm {

    protected $declaration = null;
    
    public function __construct(acCouchdbJson $declaration, $options = array(), $CSRFSecret = null) {
        $this->declaration = $declaration;
        parent::__construct($declaration, $options, $CSRFSecret);
    }
  
    public function configure() {

        
        $this->setWidget('stock_revendique', new sfWidgetFormInput());    
        $this->setWidget('produit_hash', new sfWidgetFormInput());  

        $this->widgetSchema->setLabels(array(
            'stock_revendique' => 'Volume Stock',
            'produit_hash' => 'produit_hash'
        ));

        $this->setValidators(array(
            'stock_revendique' => new sfValidatorNumber(array('required' => false)),
            'produit_hash' => new sfValidatorString(array('required' => true))
        ));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function getDeclaration()
        {
        return $this->declaration;
        }
}

