<?php
class CotisationCondition extends Cotisation
{
    
    public function getDetails($details)
    {
        $callback = $this->callback;
        if(!$this->doc->$callback()) {

            return array();
        }
        
        return parent::getDetails($details);
    }
    
}