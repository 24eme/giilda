<?php

class DSEditionForm extends acCouchdbForm {

    protected $ds = null;

    public function __construct(acCouchdbJson $ds, $defaults = array(), $options = array(), $CSRFSecret = null) {

        $this->ds = $ds;
        $defaults = array();
        foreach ($this->ds->getDeclarations() as $key => $value) {
	  $defaults['volumeStock_'.$key] = $value->stock_declare;
	  $defaults['vci_'.$key] = $value->vci;
	  $defaults['reserveQualitative_'.$key] = $value->reserve_qualitative;
        }
        $defaults['commentaires'] = $this->ds->commentaires;
        parent::__construct($ds, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        foreach ($this->ds->declarations as $key => $declaration) {
	  $this->setWidget('volumeStock_' . $key, new sfWidgetFormInputFloat(array(), array('size' => '6')));
	  $this->setWidget('vci_' . $key, new sfWidgetFormInput(array(), array('size' => '6')));
	  $this->setWidget('reserveQualitative_' . $key, new sfWidgetFormInput(array(), array('size' => '6')));

	  $this->widgetSchema->setLabel('volumeStock_' . $key, 'Volume Stock');
	  $this->widgetSchema->setLabel('vci_' . $key, 'VCI');
	  $this->widgetSchema->setLabel('reserveQualitative_' . $key, 'Reserve qualitative');
	  
	  $this->setValidator('volumeStock_' . $key, new sfValidatorNumber(array('required' => false)));
	  $this->setValidator('vci_' . $key, new sfValidatorString(array('required' => false)));
	  $this->setValidator('reserveQualitative_' . $key, new sfValidatorString(array('required' => false)));
        }
        $this->setWidget('commentaires', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));
        $this->widgetSchema->setLabel('commentaires', 'Commentaires :');
        $this->setValidator('commentaires', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setNameFormat('ds[%s]');
    }

    public function doUpdateObject() {
        $values = $this->values;

        foreach ($values as $prodKey => $volumeRev) {

            if ($prodKey == 'commentaires') {
                $this->getDocument()->commentaires = $volumeRev;
            } else {
                if (substr($prodKey, 0, strlen('volumeStock_')) === 'volumeStock_')
                    $this->updateVolumeStock(substr($prodKey,strlen('volumeStock_')), $volumeRev);
                if (substr($prodKey, 0, strlen('vci_')) === 'vci_')
                    $this->updateVCI(substr($prodKey,strlen('vci_')), $volumeRev);
                 if (substr($prodKey, 0, strlen('reserveQualitative_')) === 'reserveQualitative_')
                    $this->updateReserveQualitative(substr($prodKey,strlen('reserveQualitative_')), $volumeRev);
            }
        }
    }

    public function updateVolumeStock($prodKey, $volumeRev) {
        if ($this->getDocument()->declarations->exist($prodKey)) {
            $this->getDocument()->declarations[$prodKey]->stock_declare = $volumeRev;
        }
    }
        
    public function updateVCI($prodKey, $volumeRev) {
        if ($this->getDocument()->declarations->exist($prodKey)) {
            $this->getDocument()->declarations[$prodKey]->vci = $volumeRev;
        }
    }
    
    public function updateReserveQualitative($prodKey, $volumeRev) {
        if ($this->getDocument()->declarations->exist($prodKey)) {
            $this->getDocument()->declarations[$prodKey]->reserve_qualitative = $volumeRev;
        }
    }

}
