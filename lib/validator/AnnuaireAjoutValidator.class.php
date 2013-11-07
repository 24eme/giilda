<?php
class AnnuaireAjoutValidator extends sfValidatorBase 
{
    
    public function configure($options = array(), $messages = array()) 
    {
        $this->setMessage('invalid', "CVI ou CIVABA incorrect pour ce type d'interlocuteur.");
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
    	return AnnuaireClient::getInstance()->findTiersByTypeAndIdentifiant($values['type'], $values['identifiant']);
    }
}