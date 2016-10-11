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
        $w = array('observations' => new bsWidgetFormInput());
        $v = array('observations' => new sfValidatorString(array('required' => false)));
        $l = array('observations' => $this->_detail->getLibelle());

	if ($this->_detail->exist('replacement_date')) {
                $w['replacement'] = new bsWidgetFormInputDate();
                $v['replacement'] = new sfValidatorString(array('required' => false));
                $l['replacement'] = "Date de la sortie du volume correspondant à ce replacement";
        }
        $this->setWidgets($w);
        $this->setValidators($v);
        $this->widgetSchema->setLabels($l);
        $this->widgetSchema->setNameFormat('[%s]');
	}
}
