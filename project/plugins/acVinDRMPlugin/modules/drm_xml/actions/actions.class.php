<?php

/**
 * Description of actions
 *
 */
class drm_xmlActions extends drmGeneriqueActions {

    public function executeWait(sfWebRequest $request) {
    
    }

    public function executeTransfert(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->cielResponse = '';
        if ($xml = $this->getPartial('xml', array('drm' => $this->drm))) {
                try {
                        $service = new CielService();
                        $this->cielResponse = $service->transfer($xml);
                } catch (sfException $e) {
                        $this->cielResponse = $e->getMessage();
                }
        } else {
                $this->cielResponse = "Une erreur est survenue à la génération du XML.";
        }
        $this->setLayout(false);
        $this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
    }

    public function executePrint(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDrm();

	$this->setLayout(false);
	$this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
    }

}
