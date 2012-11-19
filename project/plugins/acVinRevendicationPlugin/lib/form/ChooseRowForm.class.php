<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class ChooseRowForm
 * @author mathurin
 */
class ChooseRowForm extends acCouchdbObjectForm {
    
    protected $num_ligne;
    protected $_rows;
    
    public function __construct(acCouchdbJson $object, $num_ligne, $options = array(), $CSRFSecret = null) {
        $this->num_ligne = $num_ligne;
        parent::__construct($object, $options, $CSRFSecret);
    }


    public function configure() {
        parent::configure();
        $this->setWidget('row_select', new sfWidgetFormChoice(array('choices' => $this->getRows(), 'expanded' => false)));
        
        $this->setValidator('row_select', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getRows()))));
        
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('chooseRow[%s]');
    }
    
    public function getRows() {
        if (is_null($this->_rows)) {
            $this->_rows = array();
            $num_ligne = $this->num_ligne;
            
            $this->addLigneToChoice();
            
            $doublons = $this->getObject()->erreurs[RevendicationErrorException::ERREUR_TYPE_DOUBLON];
            $doublonsForLigne = $doublons[$num_ligne.''];
            foreach ($doublonsForLigne as $key => $ligne_doublon) {
                $this->_rows[$ligne_doublon->num_ligne] = $ligne_doublon->num_ligne.' : '.str_replace('#',' ', $ligne_doublon->ligne);

            }
        }
        return $this->_rows;
    }
    
    public function addLigneToChoice() {
        foreach ($this->getObject()->datas as $key => $etb) {
            foreach ($etb->produits as $key => $produit) {
                foreach ($produit->volumes as $key => $volume){
                    if($volume->num_ligne == $this->num_ligne){
                        $this->_rows[$volume->num_ligne] = $volume->num_ligne.' : '.str_replace('#',' ', $volume->ligne);
                        return;
                    }
                }
            }
        }
    }




    public function getNumLigne() {
        return $this->num_ligne;
    }


    public function doUpdate() {        
        
    }
    
}