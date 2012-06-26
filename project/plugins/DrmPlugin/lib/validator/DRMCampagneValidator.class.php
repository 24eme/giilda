<?php

class DRMCampagneValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible', "Vous ne pouvez pas créer une DRM future.");
        $this->setMessage('invalid', "Cette DRM existe déjà.");
        $this->setMessage('required', "Champs obligatoires.");
    }

    protected function doClean($values) {
        if (!$values['months']) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('months') => new sfValidatorError($this, 'required')));
        }
        if (!$values['years']) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('years') => new sfValidatorError($this, 'required')));
        }
        $campagne = sprintf('%04d%02d', $values['years'], $values['months']);
        if ($campagne > date('Ym')) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('years') => new sfValidatorError($this, 'impossible')));
        }
        $campagne = sprintf('%04d-%02d', $values['years'], $values['months']);
        $tiers = sfContext::getInstance()->getUser()->getTiers();
        
        $drm = DRMClient::getInstance()->findByIdentifiantCampagneAndRectificative($tiers->identifiant, $campagne);
		
        if ($drm) {
            throw new sfValidatorErrorSchema($this, array($this->getOption('months') => new sfValidatorError($this, 'invalid')));
        }       
        $values['campagne'] = $campagne;
        return $values;
    }

}
