<?php

/**
 * Description of actions
 *
 */
class drm_xmlActions extends drmGeneriqueActions {

    public function executeWait(sfWebRequest $request) {
    
    }

    public function executeTransfert(sfWebRequest $request) {

    }

    public function executePrint(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDrm();

	$this->setLayout(false);
	$this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
    }

}
