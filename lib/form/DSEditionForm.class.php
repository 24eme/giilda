<?php

class DSEditionForm extends acCouchdbForm {

    
    protected $declarations = null;
    
    public function __construct(acCouchdbJson $declarations, $defaults = array(), $options = array(), $CSRFSecret = null) {
        
        $this->declarations = $declarations;        
        parent::__construct($this->declarations->getDocument(), $options, $CSRFSecret);
    }
    
    public function configure()
    {
        if(!count($this->declarations))
        {
            $this->declarations->add();
        }
        foreach ($this->declarations as $key => $declaration) {
            $this->setWidget($key, new sfWidgetFormInput());    
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