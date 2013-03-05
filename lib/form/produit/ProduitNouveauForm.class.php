<?php
class ProduitNouveauForm extends sfForm {
	
	protected $configuration;
	protected $configurationProduit;
	protected static $configurationNoeud = array('certifications' => 'certification', 'genres' => 'genre', 'appellations' => 'appellation', 'mentions' => 'mention', 'lieux' => 'lieu', 'couleurs' => 'couleur', 'cepages' => 'cepage'); 
	
	
	public function __construct($configuration, $interpro, $defaults = array(), $options = array(), $CSRFSecret = null) {
		$this->configuration = $configuration;
		$this->configurationProduit = new ConfigurationProduit($interpro);
		parent::__construct($defaults, $options, $CSRFSecret);
	}

    public function configure() {
    	$this->setWidgets(array(
			'certifications' => new sfWidgetFormChoice(array('choices' => $this->getLibelles('certification'))),
    		'genres' => new sfWidgetFormChoice(array('choices' => $this->getLibelles('genre'))),
			'appellations' => new sfWidgetFormChoice(array('choices' => $this->getLibelles('appellation'))),  	
			'mentions' => new sfWidgetFormChoice(array('choices' => $this->getLibelles('mention'))), 	
			'lieux' => new sfWidgetFormChoice(array('choices' => $this->getLibelles('lieu'))), 	
			'couleurs' => new sfWidgetFormChoice(array('choices' => $this->getLibelles('couleur'))),   
			'cepages' => new sfWidgetFormChoice(array('choices' => $this->getLibelles('cepage'))), 
    	));
		$this->widgetSchema->setLabels(array(
			'certifications' => 'Catégorie: ',
			'genres' => 'Genre: ',
			'appellations' => 'Dénomination: ',  	
			'mentions' => 'Mention: ', 		
			'lieux' => 'Lieu: ', 	
			'couleurs' => 'Couleur: ', 
			'cepages' => 'Cépage: '
		));
		$this->setValidators(array(
			'certifications' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getLibelles('certification')))),
			'genres' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getLibelles('genre')))),
			'appellations' => new sfValidatorString(array('required' => false)),
			'mentions' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getLibelles('mention')))),
			'lieux' => new sfValidatorString(array('required' => false)),
			'couleurs' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getLibelles('couleur')))),
			'cepages' => new sfValidatorString(array('required' => false)),
		));
		
        $this->widgetSchema->setNameFormat('produit[%s]');
    }

    public function getLibelles($noeud) {

        return $this->configuration->declaration->getLibelleByKeys($noeud);
    }
    
    public function save() {
        $values = $this->getValues();
        $hash = 'declaration';
        $nodes = ConfigurationProduit::getArborescence();
        $noeud = null;
        foreach ($nodes as $node) {
	        if ($values[$node]) {
	        	if ($this->configuration->exist($hash.'/'.$node.'/'.$values[$node])) {
	        		$hash = $hash.'/'.$node.'/'.$values[$node];
	        		$noeud = self::$configurationNoeud[$node];
	        		unset($values[$node]);
	        	} else {
	        		$hash = $hash.'/'.$node.'/'.Configuration::DEFAULT_KEY;
	        	}
	        } else {
	        	$hash = $hash.'/'.$node.'/'.Configuration::DEFAULT_KEY;
	        	unset($values[$node]);
	        }
        }

        print_r(array('hash' => $hash, 'noeud' => $noeud, 'values' => $values));
        exit;
        return array('hash' => $hash, 'noeud' => $noeud, 'values' => $values);
    }
    
}