<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneModificationForm
 * @author mathurin
 */
class VracSoussigneModificationForm extends acCouchdbFormDocumentJson {
    
   private $type = null;
   
    public function configure()
    {
        $this->type = $this->getObject()->famille;
        
        if($this->type == "Viticulteur") $this->configureAcheteurVendeur('vendeur');
        if($this->type == "Negociant") $this->configureAcheteurVendeur('acheteur');
        if($this->type == "Courtier") $this->configureMandataire();
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        //$this->getObject()->setInformations();
    }
    
    private function configureAcheteurVendeur($label)
    {    
        $this->setWidget('cvi', new sfWidgetFormInput());        
        $this->setWidget('num_accise', new sfWidgetFormInput());        
        $this->setWidget('adresse', new sfWidgetFormInput());        
        $this->setWidget('code_postal', new sfWidgetFormInput());   
        $this->setWidget('commune', new sfWidgetFormInput());
        $this->setWidget('num_tva_intracomm', new sfWidgetFormInput());
        
        
        $this->widgetSchema->setLabels(array(
            'cvi' => 'N° CVI',
            'num_accise' => 'N° ACCISE',
            'adresse' => 'Adresse*',
            'code_postal' => 'CP',
            'commune' => 'Ville*',
            'num_tva_intracomm' => 'TVA Intracomm.'
        ));
                
        $this->setValidators(array(
            'cvi' => new sfValidatorNumber(array('required' => false)),
            'num_accise' => new sfValidatorString(array('required' => false)),
            'adresse' => new sfValidatorString(array('required' => true, 'min_length' => 5)),
            'code_postal' => new sfValidatorString(array('required' => false, 'min_length' => 5,'max_length' => 5)),
            'commune' => new sfValidatorString(array('required' => true, 'min_length' => 2)),
            'num_tva_intracomm' => new sfValidatorString(array('required' => false))
            ));
        
        $this->widgetSchema->setNameFormat('vrac[%s]');    
    }
    
    private function configureMandataire() {
                
        $this->setWidget('carte_pro', new sfWidgetFormInput());          
        $this->setWidget('adresse', new sfWidgetFormInput());        
        $this->setWidget('code_postal', new sfWidgetFormInput());
        $this->setWidget('commune', new sfWidgetFormInput());   
        
        $this->widgetSchema->setLabels(array(
            'carte_pro' => 'N° carte professionnelle',
            'adresse' => 'Adresse',
            'code_postal' => 'CP',
            'commune' => 'Ville'
        ));
        
       $this->setValidators(
       array(
            'carte_pro' => new sfValidatorNumber(array('required' => false)),
            'adresse' => new sfValidatorString(array('required' => true, 'min_length' => 5)),
            'code_postal' => new sfValidatorString(array('required' => false, 'min_length' => 5,'max_length' => 5)),
            'commune' => new sfValidatorString(array('required' => false, 'min_length' => 2))
            ));
       $this->widgetSchema->setNameFormat('vrac[%s]');        
    }


}

?>
