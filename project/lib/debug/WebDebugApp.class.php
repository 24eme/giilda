<?php

class WebDebugApp extends sfWebDebug
{
    public function configure()
    {
        parent::configure();
        if (sfConfig::get('sf_debug'))
        {
            $this->setPanel('couchdb', new sfWebDebugPanelCouchdb($this));
        }
    }
}
