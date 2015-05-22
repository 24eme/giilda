<?php

class AddAliasToEtablissementForm  extends acCouchdbObjectForm {

    protected $etablissement;
    protected $alias;
    
    public function __construct(acCouchdbJson $object, $alias,  $options = array(), $CSRFSecret = null) {
        $this->etablissement = $object;
        $this->alias = $alias;
        parent::__construct($object, $options, $CSRFSecret);
    }


    public function configure() {
        parent::configure();
        $this->setWidget('bailleur', new sfWidgetFormChoice(array('choices' => $this->getBailleurs()), array('class' => 'autocomplete')));
        $this->setValidator('bailleur', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getBailleurs()))));
        
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('addAlias[%s]');
    }
    
    
    public function doUpdate() {    
        $this->etablissement->addAliasForBailleur($this->values['bailleur'],$this->alias);
        $this->etablissement->save();
    }
    
    public function getBailleurs() {
        $bailleurs = $this->etablissement->getBailleurs();
        $choices = array();
        foreach ($bailleurs as $bailleur) {
            $choices[$bailleur->id_etablissement] = $bailleur->libelle_etablissement;
        }
        return $choices;
    }
    
}