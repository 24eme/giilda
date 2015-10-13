<?php
class FactureEditionLigneDetailValidator extends sfValidatorSchema 
{
    
    public function configure($options = array(), $messages = array()) {
    
    }

    protected function doClean($values) {
        if(!is_numeric($values['quantite']) && empty($values['libelle']) && !is_numeric($values['prix_unitaire']) && !is_numeric($values['prix_unitaire'])) {
            
            return $values;
        }

        $errors = array();

        if(!is_numeric($values['quantite'])) {
            $errors['quantite'] = new sfValidatorError($this, 'required');
        }

        if(empty($values['libelle'])) {
            $errors['libelle'] = new sfValidatorError($this, 'required');
        }

        if(!is_numeric($values['prix_unitaire'])) {
            $errors['prix_unitaire'] = new sfValidatorError($this, 'required');
        }

        if(!is_numeric($values['taux_tva'])) {
            $errors['taux_tva'] = new sfValidatorError($this, 'required');
        }

        if(count($errors)) {

            throw new sfValidatorErrorSchema($this, $errors);
        }

        return $values;
    }
}