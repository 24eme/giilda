<?php
class StatistiqueStatsFilterForm extends BaseForm
{
	protected $config;
	
	public function __construct($config, $defaults = array(), $options = array(), $CSRFSecret = null)
	{
		$this->config = $config;
		parent::__construct($defaults, $options, $CSRFSecret);
	}
  	
	public function configure() 
	{
		$this->setWidgets(array(
				'appellations' => new bsWidgetFormChoice(array('multiple' => true, 'choices' => self::getLibelles('appellation')), array('class' => 'select2 form-control')),
				'familles' => new bsWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => self::getFamilles())),
				'region' => new bsWidgetFormChoice(array('choices' => self::getRegions())),
				'lastyear' => new bsWidgetFormInputCheckbox(),
				'dates' => new sfWidgetFormFilterDate(array('from_date' => new bsWidgetFormInputDate(), 'to_date' => new bsWidgetFormInputDate(), 'with_empty' => false, 'template' => 'Du %from_date% <br />Au %to_date%')),
				'statistiques' => new bsWidgetFormChoice(array('multiple' => false, 'expanded' => true, 'choices' => $this->getStatistiques())),
		));
		
		$this->widgetSchema->setLabels(array(
				'appellations' => 'Appellation',
				'familles' => 'Catégorie',
				'region' => 'Région',
				'dates' => '',
				'lastyear' => 'Stat N/N-1',
				'statistiques' => 'Statistiques',
		));
		
		$this->setValidators(array(
				'appellations' => new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys(self::getLibelles('appellation')))),
				'familles' => new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys(self::getFamilles()))),
				'region' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys(self::getRegions()))),
				'dates' => new sfValidatorDateRange(array('from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)), 'from_field' => 'from_date', 'to_field' => 'to_date')),
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
    
    public function processFilters($statFilters)
    {
    	$values = $this->getValues();
    	$conf = $this->getStatistiquesConf();
    	if ($values['appellations']) {
    		foreach ($values['appellations'] as $item) {
    			$statFilters->addFilter($conf['form_filters']['appellations']['ind'], new RegexpStatFilter("/$item/"));
    		}
    	}
    	if ($values['familles']) {
    		foreach ($values['familles'] as $item) {
    			$statFilters->addFilter($conf['form_filters']['familles']['ind'], new RegexpStatFilter("/$item/"));
    		}
    	}
    	if ($values['region']) {
    		$statFilters->addFilter($conf['form_filters']['region']['ind'], new RegexpStatFilter("/".$values['region']."/"));
    	}
    	if ($values['dates'] && ($values['dates']['from'] || $values['dates']['to'])) {
    		$statFilters->addFilter($conf['form_filters']['dates']['ind'], new RangeStatFilter(array('gte' => $this->getPeriodeFromDate($values['dates']['from']), 'lte' => $this->getPeriodeFromDate($values['dates']['to']))));
    	}
    	if (isset($this->config['statistiques'][$values['statistiques']]['filters'])) {
    		foreach ($this->config['statistiques'][$values['statistiques']]['filters'] as $ind => $regexp) {
    			$statFilters->addFilter($ind, new RegexpStatFilter($regexp));
    		}
    		
    	}
    	return $statFilters;
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