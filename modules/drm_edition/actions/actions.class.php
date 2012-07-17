<?php

class drm_editionActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->init();

        $this->setTemplate('index');
    }

    public function executeDetail(sfWebRequest $request) {
        $this->init();
        $this->detail = $this->getRoute()->getDRMDetail();
        $this->setTemplate('index');
    }
    
    public function executeUpdate(sfWebRequest $request) {
        $this->init();
  
        $this->form = new DRMDetailForm($this->getRoute()->getDRMDetail());
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if($this->form->isValid()) {
        	$this->form->save();
            if ($request->isXmlHttpRequest()) {
				         		
                return $this->renderText(json_encode(array(
                	"success" => true,
                	"content" => "",
                	"document" => array("id" => $this->drm->get('_id'),
                	"revision" => $this->drm->get('_rev'))
                	)));
            } else {
                $this->redirect('drm_edition', $this->config_lieu);
            }
        }
        
        if ($request->isXmlHttpRequest()) {
            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('drm_recap/itemFormErrors', array('form' => $this->form)))));
        } else {
            $this->setTemplate('index');
        }
    }

    public function executeProduitAjout(sfWebRequest $request) {
    	$this->init();

    	$this->form = new DRMProduitForm($this->drm, $this->drm->declaration->getConfig());
     	$this->form->bind($request->getParameter($this->form->getName()));
     	if ($this->form->isValid()) {
     		$detail = $this->form->addProduit();	
     		$this->drm->save();
     		if ($request->isXmlHttpRequest()) {
     			return $this->renderText(json_encode(array(
     				"success" => true,
     				"content" => $this->getComponent('drm_edition', 'itemForm', array('config' => $this->config, 'detail' => $detail, 'active' => true)),
     				"document" => array("id" => $this->drm->get('_id'),
                	"revision" => $this->drm->get('_rev'))
     			)));
     		} else {
     			$this->redirect('drm_edition', $this->drm);
     		}
     	}

     	if ($request->isXmlHttpRequest()) {
     		return $this->renderText(json_encode(array(
     				"success" => false,
     				"content" => ""
     		)));
        } else {
            $this->redirect('drm_edition', $this->drm);
        }
    }
    
    protected function init() {
        $this->form = null;
        $this->detail = null;
        $this->drm = $this->getRoute()->getDRM();
        $this->config = $this->drm->declaration->getConfig();
        $this->produits = $this->drm->declaration->getProduits();
    }

    public function executeAddLabel(sfWebRequest $request) 
    {
      $detail = $this->getRoute()->getDRMDetail();
      $drm = $this->getRoute()->getDRM();
      $this->form = new DRMProduitLabelForm($detail);
      if ($request->isMethod('POST')) {
	$this->form->bind($request->getParameter($this->form->getName()));
	if ($this->form->isValid()) {
	  $this->form->save();	
	  return $this->redirect('drm_edition', $drm);
	}
      }
    }
}
