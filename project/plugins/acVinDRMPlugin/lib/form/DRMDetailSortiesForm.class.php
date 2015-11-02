<?php

class DRMDetailSortiesForm  extends acCouchdbObjectForm {

    public function configure() {
    	$configurationDetail = $this->getObject()->getParent()->getConfig();
        $certif = $this->getObject()->getParent()->getCertification()->getKey();
        $drm = $this->getObject()->getDocument();
    	foreach ($configurationDetail->getSortiesSorted() as $key => $value) {
            $disabled = (!preg_match('/AOC|IGP/', $certif) && ($key == 'repli'));
            if ($key == 'contrathorsinterpro' && preg_match('/_INTERLOIRE/', $certif)) {
                $disabled = true;
            }
            if ($key == 'contrat' && !preg_match('/_INTERLOIRE/', $certif)) {
                $disabled = true;
            }
    		if ($value->readable) {
	    		if (!$value->writable || $disabled) {
	    			$this->setWidget($key, new bsWidgetFormInputFloat(array('decimal' => 4), array('readonly' => 'readonly')));
	    		} else {
	    			$this->setWidget($key, new bsWidgetFormInputFloat(array('decimal' => 4)));
	    		}
	    		$this->setValidator($key, new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));
    		}
    	}        
        $this->widgetSchema->setNameFormat('drm_detail_sorties[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}