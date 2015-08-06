<?php

class DRMDetailSortiesForm  extends acCouchdbObjectForm {

    public function configure() {
    	$configurationDetail = $this->getObject()->getParent()->getConfig();
        $drm = $this->getObject()->getDocument();
    	foreach ($configurationDetail->getSortiesSorted() as $key => $value) {
    		if ($value->readable) {
	    		if (!$value->writable) {
	    			$this->setWidget($key, new sfWidgetFormInputFloat(array(), array('readonly' => 'readonly')));
	    		} else {
	    			$this->setWidget($key, new sfWidgetFormInputFloat());
	    		}
	    		$this->setValidator($key, new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));
    		}
    	}        
        $this->widgetSchema->setNameFormat('drm_detail_sorties[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}