<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactureMouvementsEditionValidator
 *
 * @author mathurin
 */
class FactureMouvementsEditionValidator extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        
    }

    protected function doClean($values) {
//        if (empty($values['quantite']) && empty($values['libelle']) && empty($values['prix_unitaire'])) {
//            return $values;
//        }
//
//        $errors = array();
//
//        if (empty($values['libelle'])) {
//            $errors['libelle'] = new sfValidatorError($this, 'required');
//        }
//
//        if (count($errors)) {
//
//            throw new sfValidatorErrorSchema($this, $errors);
//        }

        return $values;
    }

}
