<?php
class FactureEditionLigneValidator extends sfValidatorSchema 
{
    
    public function configure($options = array(), $messages = array()) {
    
    }

    protected function doClean($values) {
        $hasDetails = false;
        foreach($values['details'] as $key_detail => $detail) {
            if(empty($detail['quantite']) && empty($detail['libelle']) && empty($detail['prix_unitaire'])) {
                continue;   
            }

            $hasDetails = true;
        }

        if(!$hasDetails) {

            return $values;
        }

        $errors = array();

        if(empty($values['libelle'])) {
            $errors['libelle'] = new sfValidatorError($this, 'required');
        }

        if(empty($values['produit_identifiant_analytique'])) {
            $errors['produit_identifiant_analytique'] = new sfValidatorError($this, 'required');
        }

        if(count($errors)) {

            throw new sfValidatorErrorSchema($this, $errors);
        }

        return $values;
    }
}