<?php
class StatistiqueStatsFilterForm extends BaseForm
{
	protected $config;
	protected static $rangeFields = array('doc.mouvements.date');
	
	public function __construct($config, $defaults = array(), $options = array(), $CSRFSecret = null)
	{
		$this->config = $config;
		parent::__construct($defaults, $options, $CSRFSecret);
	}
  	
	public function configure() 
	{
		$this->setWidgets(array(
				'doc.mouvements.appellation' => new bsWidgetFormChoice(array('multiple' => true, 'choices' => self::getLibelles('appellation')), array('class' => 'select2 form-control')),
				'doc.declarant.famille' => new bsWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => self::getFamilles())),
				'doc.mouvements.region' => new bsWidgetFormChoice(array('choices' => self::getRegions())),
				'lastyear' => new bsWidgetFormInputCheckbox(),
				'doc.mouvements.date' => new sfWidgetFormFilterDate(array('from_date' => new bsWidgetFormInputDate(), 'to_date' => new bsWidgetFormInputDate(), 'with_empty' => false, 'template' => 'Du %from_date% <br />Au %to_date%')),
				'statistiques' => new bsWidgetFormChoice(array('multiple' => false, 'expanded' => true, 'choices' => $this->getStatistiques())),
		));
		
		$this->widgetSchema->setLabels(array(
				'doc.mouvements.appellation' => 'Appellation',
				'doc.declarant.famille' => 'Catégorie',
				'doc.mouvements.region' => 'Région',
				'doc.mouvements.date' => 'Période',
				'lastyear' => 'Stat N/N-1',
				'statistiques' => 'Statistiques',
		));
		
		$this->setValidators(array(
				'doc.mouvements.appellation' => new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys(self::getLibelles('appellation')))),
				'doc.declarant.famille' => new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys(self::getFamilles()))),
				'doc.mouvements.region' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys(self::getRegions()))),
				'doc.mouvements.date' => new sfValidatorDateRange(array('from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)), 'from_field' => 'from_date', 'to_field' => 'to_date')),
				'lastyear' => new ValidatorBoolean(array('required' => false)),
				'statistiques' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getStatistiques()))),
		));
        $this->widgetSchema->setNameFormat('statistique_filter[%s]');
    }
    
    public function getStatistiques() {
    	$statistiques = array();
    	foreach ($this->config['statistiques'] as $key => $value) {
    		$statistiques[$key] = $value['libelle'];
    	}
    	return $statistiques;
    }
    
    public static function getFamilles() {
    	return EtablissementFamilles::getFamilles();
    }
    
    public static function getRegions() {
    	return array_merge(array(null => null), EtablissementClient::getRegions());
    }

    public static function getLibelles($noeud) {
        $libelles = array();
        $items = self::getItems($noeud);

        foreach($items as $key => $item) {
            $libelles[$key] = $item->getLibelle();
        }

        return $libelles;
    }

    public static function getItems($noeud) {

        return ConfigurationClient::getCurrent()->declaration->getKeys($noeud);
    }
    
    public function processFilters()
    {
    	$values = $this->getValues();
    	unset($values['statistiques'], $values['lastyear']);
    	$rangeFields = self::$rangeFields;
    	$nbFilters = 0;
    	$filters = array();
    	foreach ($values as $field => $value) {
    		if (in_array($field, $rangeFields)) {
    			$range = array('format' => 'dd/MM/yyyy');
    			if ($value['from']) {
    				$range['gte'] = $value['from'];
    			}
    			if ($value['to']) {
    				$range['lte'] = $value['to'];
    			}
    			$filters[] = array('range' => array($field => $range));
    			$nbFilters++;
    		} elseif ($value) {
    			$filter = (is_array($value))? 'terms' : 'term';
    			$filters[] = array($filter => array($field => $value));
    			$nbFilters++;
    		}
    	}
    	return ($nbFilters > 0)? ($nbFilters > 1)? array('filtered' => array('filter' => array('and' => $filters))) : array('filtered' => array('filter' => current($filters))) : null;
    }
    
    public function getStatistiquesConf()
    {
    	$values = $this->getValues();
    	return $this->config['statistiques'][$values['statistiques']];
    }
    
    protected function getPeriodeFromDate($date)
    {
    	if (!$date) {
    		return null;
    	}
    	$d = explode('/', $date);
    	return ($date)? sprintf('%s', date('Ym', strtotime($d[2].'-'.$d[1].'-'.$d[0]))) : null;
    }
    
   
}