<?php
class AnnuaireAjoutValidator extends sfValidatorBase 
{
    
    public function configure($options = array(), $messages = array()) 
    {
        $this->setMessage('invalid', "CVI incorrect pour ce type d'interlocuteur.");
    }

    protected function doClean($values) 
    {
        $tiers = $this->getTiers($values);
        if (!$tiers && $values['tiers']) {
            throw new sfValidatorErrorSchema($this, array('tiers' => new sfValidatorError($this, 'invalid')));
        }
        return array_merge($values, array('etablissement' => $tiers));
    }
    
    protected function getTiers($values)
    {
    	return AnnuaireClient::getInstance()->findTiersByTypeAndTiers($values['type'], $values['tiers']);
    }
}