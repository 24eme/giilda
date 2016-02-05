<?php
class ProduitDroitForm extends BaseForm {

    public function configure() {
    	$this->setWidgets(array(
			'date' => new bsWidgetFormInput( array('default' => ''), array('class' => 'hasDatepicker') ),
			'taux' => new bsWidgetFormInput()  		
    	));
		$this->widgetSchema->setLabels(array(
			'date' => 'Date: ',
			'taux' => 'Taux: '
		));
                
               
		$this->setValidators(array(
			'date' => new sfValidatorString(array('required' => false)),
			'taux' => new sfValidatorString(array('required' => false))
		));
		if ($droit = $this->getOption('droit')) {
			$date = new DateTime($droit->date);
			$this->setDefaults(array(
	    		'date' => $date->format('d/m/Y'),
	    		'taux' => $droit->taux
	    	));
		}		
        $this->widgetSchema->setNameFormat('produit_droit[%s]');
    }
}