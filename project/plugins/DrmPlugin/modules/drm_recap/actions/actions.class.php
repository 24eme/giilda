<?php

class drm_recapActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->init();
    	$find = false;
        $next_certif = null;
        $certif = $this->config_lieu->getCertification()->getKey();
        $config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
        foreach ($config_certifications as $certification_config) {
        	if ($this->drm->exist($certification_config->getHash())) {
            	if ($find) {
                	$next_certif = $certification_config->getKey();
                	break;
                }
                if ($certif == $certification_config->getKey()) {
                	$find = true;
                } else {
                	$this->prev_certif = $certification_config->getKey();
                }
            }
        }
        if ($request->isMethod(sfWebRequest::POST)) {
            if ($next_certif) {
        		$this->redirect('drm_recap', $this->drm->declaration->certifications->get($next_certif));
            } else {
        		$this->drm->setCurrentEtapeRouting('vrac');
        		$this->redirect('drm_vrac', $this->drm);
            }
        }
        $this->setTemplate('index');
    }
    
    public function executeRedirectIndex(sfWebRequest $request) {
    	$drm = $this->getRoute()->getDrm();
    	$first_certification = null;
        if(count($drm->declaration->certifications) > 0) {
            $first_certification = $drm->declaration->certifications->getFirst();
        }
        $this->redirect('drm_recap', $first_certification);
    }
    
    public function executeLieuAjoutAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->getResponse()->setContentType('text/json');
        $this->certification = $this->getRoute()->getObject();
        $this->drm = $this->getRoute()->getDrm();

        $this->form = new DRMLieuAjoutForm($this->drm, $this->certification->getConfig());
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $lieu = $this->form->addLieu();
                return $this->renderText(json_encode(array("success" => true,
                                                           "ajax" => true,
                                                           "url" => $this->generateUrl('drm_recap_ajout_ajax', $lieu))));
            }

            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('drm_recap/formLieuAjout', array('certification' => $this->certification, 'form' => $this->form)))));
        }
		
        return $this->renderText($this->getPartial('drm_recap/popupLieuAjout', array('certification' => $this->certification, 'form' => $this->form)));
    }
    
    public function executeLieu(sfWebRequest $request) {
        $this->init();
        $this->setTemplate('index');
    }

    public function executeAjoutAjax(sfWebRequest $request) 
    {
        $this->init();
        $this->forward404Unless($request->isXmlHttpRequest());

        $form = new DRMProduitAjoutForm($this->drm,
                                        $this->config_lieu);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                $form->addProduit();
                $this->drm->save();
                $this->getUser()->setFlash("notice", 'Le produit a été ajouté avec succès.');
                return $this->renderText(json_encode(array("success" => true, 
                                                           "url" => $this->generateUrl('drm_recap_lieu', $this->drm_lieu))));
            }
            return $this->renderText(json_encode(array("success" => false, 
                                                       "content" => $this->getPartial('formAjout', array('form' => $form, 'drm_lieu' => $this->drm_lieu)))));
        }
        return $this->renderPartial('popupAjout', array('form' => $form, 'drm_lieu' => $this->drm_lieu));
    }
    
    public function executeDetail(sfWebRequest $request) {
        $this->init();
        $this->light_detail = $this->getRoute()->getDRMDetail();
        $this->setTemplate('index');
    }
    
    public function executeUpdate(sfWebRequest $request) {
        $this->init();
  
        $this->form = new DRMDetailForm($this->getRoute()->getDrmDetail());
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if($this->form->isValid()) {
            $this->form->save();
            if ($request->isXmlHttpRequest()) {
                return $this->renderText(json_encode(array("success" => true, "content" => "")));
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
    
    protected function init() {
        $this->form = null;
        $this->drm = $this->getRoute()->getDrm();
        $this->config_lieu = $this->getRoute()->getConfigLieu();
        $this->drm_lieu = $this->getRoute()->getDrmLieu();
        $this->produits = $this->drm_lieu->getProduits();
        $this->previous = $this->drm_lieu->getPreviousSisterWithMouvementCheck();
        $this->next = $this->drm_lieu->getNextSisterWithMouvementCheck();
    	$this->prev_certif = null;
    	$this->light_detail = null;
    }
    
}
