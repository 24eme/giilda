<?php
class AnnuaireAjoutValidator extends sfValidatorBase
{
    protected $societeChoice;

    public function __construct($societeChoice, $options = array(), $messages = array()) {
        parent::__construct($options, $messages);
        $this->societeChoice = $societeChoice;
    }



    public function configure($options = array(), $messages = array())
    {
        $this->setMessage('invalid', "Identifiant incorrect pour ce type d'interlocuteur.");
    }

    protected function doClean($values)
    {
        $tiers = $this->getTiers($values);
        if (!$tiers && $values['tiers']) {
            throw new sfValidatorErrorSchema($this, array('tiers' => new sfValidatorError($this, 'invalid')));
        }
        return array_merge($values, array('societe' => $tiers));
    }

    protected function getTiers($values)
    {
      $type = (isset($values['type']))? $values['type'] : null;
      if(!$type){
        $type = AnnuaireClient::ANNUAIRE_RECOLTANTS_KEY;
      }
    	return AnnuaireClient::getInstance()->findSocieteByTypeAndTiers($type, $values['tiers']);
    }
}
