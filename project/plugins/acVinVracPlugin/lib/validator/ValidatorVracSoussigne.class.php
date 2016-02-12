<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidatorVracDomaine
 *
 * @author mathurin
 */
class ValidatorVracSoussigne extends sfValidatorBase {

    public function __construct($options = array(), $messages = array()) {
        parent::__construct($options, $messages);
        $this->setMessage('required', 'Le choix d\'un acheteur est obligatoire');
    }
    
    protected function doClean($values) {

        $errorSchema = new sfValidatorErrorSchema($this);
        if ($values['acheteur_type'] == EtablissementFamilles::FAMILLE_PRODUCTEUR) {
        	if (!$values['acheteur_producteur']) {
            	$errorSchema->addError(new sfValidatorError($this, 'required'), 'acheteur_producteur');
        	}
        } else {
        	if (!$values['acheteur_negociant']) {
            	$errorSchema->addError(new sfValidatorError($this, 'required'), 'acheteur_negociant');
        	}
        }
        if (count($errorSchema)) {
            throw new sfValidatorErrorSchema($this, $errorSchema);
        }

        return $values;
    }

}
