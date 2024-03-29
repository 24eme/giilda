<?php
class ProduitDroitForm extends BaseForm {

    public function configure() {
    	$this->setWidgets(array(
			'date' => new bsWidgetFormInputDate(),
			'taux' => new bsWidgetFormInput()  		
    	));
		$this->widgetSchema->setLabels(array(
			'date' => 'Date: ',
			'taux' => 'Taux: '
		));
                
               
		$this->setValidators(array(
			'date' => new sfValidatorDate(array('required' => false, 'datetime_output' => 'Y-m-d', 'date_format' => '~(?<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~')),
			'taux' => new sfValidatorString(array('required' => false))
		));
		if ($droit = $this->getOption('droit')) {
  
			$date = new DateTime($droit->date);
			$this->setDefaults(array(
	    		'date' => $date->format('d/m/Y'),
	    		'taux' => $droit->getStringTaux(true)
	    	));
		}		
        $this->widgetSchema->setNameFormat('produit_droit[%s]');
    }
    
   
}