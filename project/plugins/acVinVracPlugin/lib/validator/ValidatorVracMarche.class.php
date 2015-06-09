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
class ValidatorVracMarche extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('invalid_quantite', 'La quantité doit être renseignée.');
        $this->addMessage('invalid_domaine', 'Le domaine doit être renseigné.');
    }

    protected function doClean($values) {

        $errorSchema = new sfValidatorErrorSchema($this);

        if (($values['categorie_vin'] === VracClient::CATEGORIE_VIN_DOMAINE) && !$values['domaine']) {
            $errorSchema->addError(new sfValidatorError($this, 'invalid_domaine'), 'domaine');
        }
        if (count($errorSchema)) {
            throw new sfValidatorErrorSchema($this, $errorSchema);
        }

        return $values;
    }

}
