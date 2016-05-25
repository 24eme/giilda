<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class FactureLigne
 * @author mathurin
 */
class FactureDetail extends BaseFactureDetail {

   public function getIdentifiantAnalytique() {
	if ($this->exist('identifiant_analytique')) {
		return $this->_get('identifiant_analytique');
        }
	return ($this->getParent()->getParent()->exist('identifiant_analytique'))? $this->getParent()->getParent()->get('identifiant_analytique') : null;
   }

}
