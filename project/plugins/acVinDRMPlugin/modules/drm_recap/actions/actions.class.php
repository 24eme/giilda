<?php

class drm_recapActions extends sfActions
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
                $this->redirect('drm_recap_lieu', $this->config_lieu);
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
     				"content" => $this->getComponent('drm_recap', 'itemForm', array('config' => $this->config, 'detail' => $detail, 'active' => true)),
     				"document" => array("id" => $this->drm->get('_id'),
                	"revision" => $this->drm->get('_rev'))
     			)));
     		} else {
     			$this->redirect('drm_recap_lieu', $this->drm_lieu);
     		}
     	}

     	if ($request->isXmlHttpRequest()) {
     		return $this->renderText(json_encode(array(
     				"success" => false,
     				"content" => ""
     		)));
        } else {
            $this->redirect('drm_recap_lieu', $this->drm_lieu);
        }
    }
    
    protected function init() {
        $this->form = null;
        $this->detail = null;
        $this->drm = $this->getRoute()->getDRM();
        $this->config = $this->drm->declaration->getConfig();
        $this->produits = $this->drm->declaration->getProduitsDetails();
        /*$this->previous = $this->drm_lieu->getPreviousSisterWithMouvementCheck();
        $this->next = $this->drm_lieu->getNextSisterWithMouvementCheck();
    	$this->previous_certif = $this->drm_lieu->getCertification()->getPreviousSisterWithMouvementCheck();
    	$this->next_certif = $this->drm_lieu->getCertification()->getNextSisterWithMouvementCheck();

    	$this->redirectIfNoMouvementCheck();*/
    }

    protected function redirectIfNoMouvementCheck() {    	
    	if (!$this->drm_lieu->hasMouvementCheck()) {
	    	if ($this->next) {
	        	$this->redirect('drm_recap_lieu', $this->next);
	        } elseif (!$this->next && $this->next_certif) {
	        	$this->redirect('drm_recap', $this->next_certif);
	        } else  {
	        	$this->redirect('drm_vrac', $this->drm);
	        }
    	}
    }
}
