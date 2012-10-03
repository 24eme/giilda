<?php
class DSGenerationForm extends DSGenerationOperateurForm {

    private $regions = array('angers' => 'Angers',
                             'nantes' => 'Nantes',
                             'tours' => 'Tours');
    
    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure()
    {
        parent::configure();
        $this->setWidget('region', new sfWidgetFormChoice(array('choices' => $this->getRegions(), 'multiple' => true, 'expanded' => true, 'default'=>array_keys($this->getRegions()))));
        $this->setWidget('operateur_type', new sfWidgetFormChoice(array('choices' => $this->getOperateurs(), 'multiple' => true, 'expanded' => true, 'default'=>array_keys($this->getOperateurs()))));
         
	$choix = array_keys($this->getRegions());
	$this->setValidator('region', new sfValidatorChoice(array('choices' => $choix, 'multiple' => true)));
	$this->setValidator('operateur_type', new sfValidatorChoice(array('choices' => array_keys($this->getOperateurs()), 'multiple' => true)));

        $this->widgetSchema->setLabels(array(
            'region' => 'Sélectionner les régions concernées :',
            'operateur_type' => "Choisir le type d'opérateur :"
        ));
        $this->widgetSchema->setNameFormat('ds_generation[%s]');
    }

    public function getRegions() {
        return $this->regions;
    }

    public function getOperateurs() {
        return array(EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::FAMILLE_NEGOCIANT);
    }

}