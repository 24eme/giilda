<?php
class AnnuaireAjoutValidator extends sfValidatorBase 
{
    
    public function configure($options = array(), $messages = array()) 
    {
        $this->setMessage('invalid', "Ce cvi n'existe pas");
    }

    protected function doClean($values) 
    {
        $tiers = $this->getTiers($values);
        if (!$tiers && $values['identifiant']) {
            throw new sfValidatorErrorSchema($this, array('identifiant' => new sfValidatorError($this, 'invalid')));
        }
        return array_merge($values, array('tiers' => $tiers));
    }
    
    protected function getTiers($values)
    {
    	switch ($values['type']) {
    		case AnnuaireClient::ANNUAIRE_ACHETEURS_KEY :
    			$tiers = AcheteurClient::getInstance()->retrieveByCvi($values['identifiant']);
    			break;
    		case AnnuaireClient::ANNUAIRE_VENDEURS_KEY :
    			$tiers = RecoltantClient::getInstance()->retrieveByCvi($values['identifiant']);
    			break;
    		default:
    			$tiers = null;
    			break;
    			
    	}
    	return $tiers;
    }
}