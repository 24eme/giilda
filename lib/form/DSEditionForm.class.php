<?php

class DSEditionForm extends acCouchdbForm {

    
    protected $ds = null;
    
    public function __construct(acCouchdbJson $ds, $defaults = array(), $options = array(), $CSRFSecret = null) {
        
        $this->ds = $ds;
        $defaults = array();
        foreach ($this->ds->getDeclarations() as $key => $value) {
            $defaults[$key] = $value->stock_revendique;
        }      
        $defaults['commentaires'] = $this->ds->commentaires;
        parent::__construct($ds,$defaults, $options, $CSRFSecret);
    }
    
    public function configure()
    {
        foreach ($this->ds->declarations as $key => $declaration) {
            $this->setWidget($key, new sfWidgetFormInputFloat());    
            $this->widgetSchema->setLabel($key, 'Volume Stock');
            $this->setValidator($key, new sfValidatorNumber(array('required' => false)));
        }
        $this->setWidget('commentaires', new sfWidgetFormTextarea(array(),array('style' => 'width: 100%;resize:none;')));
        $this->widgetSchema->setLabel('commentaires','Commentaires :');
        $this->setValidator('commentaires' , new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setNameFormat('ds[%s]');        
    }
    
public function doUpdateObject() {
    $values = $this->values;
    
    foreach ($values as $prodKey => $volumeRev) {
        
        if($prodKey == 'commentaires'){
            $this->getDocument()->commentaires = $volumeRev;
        }
        else{
        if($this->getDocument()->declarations->exist($prodKey))
        {
            $this->getDocument()->declarations[$prodKey]->stock_revendique = $volumeRev;
        }
        }
    }
}

}