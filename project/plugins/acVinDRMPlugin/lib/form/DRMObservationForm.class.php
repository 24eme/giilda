<?php

class DRMObservationForm extends BaseForm
{
	protected $_detail;

	public function __construct($detail, $options = array(), $CSRFSecret = null)
	{
		$this->_detail = $detail;
		parent::__construct($this->getDefaultValues(), $options, $CSRFSecret);
	}

    public function getDefaultValues() {
			$defaults = array();
			if($this->_detail->exist('observations')){
    		$defaults = array('observations' => $this->_detail->observations);
			}
    	return  $defaults;
    }

	public function configure()
	{
		$this->setWidgets(array(
            'observations' => new sfWidgetFormInput()
        ));
        $this->setValidators(array(
            'observations' => new sfValidatorString(array('required' => false))
        ));
        $this->widgetSchema->setLabels(array(
        	'observations' => $this->_detail->getLibelle()
        ));
        $this->widgetSchema->setNameFormat('[%s]');
	}
}
