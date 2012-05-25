<?php

/**
 * vrac actions.
 *
 * @package    vinsdeloire
 * @subpackage vrac
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vracActions extends sfActions
{

  public function executeIndex(sfWebRequest $request)
  {
      
  }
  
  
  public function executeNouveau(sfWebRequest $request)
  {
      $this->vrac = new Vrac();
      $this->form = new VracSoussigneForm($this->vrac);
      if ($request->isMethod(sfWebRequest::POST)) 
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid())
            {
                $this->vrac->numero_contrat = VracClient::getInstance()->getNextNoContrat();
                $this->form->save();      
                $this->redirect('vrac_marche', $this->vrac);
            }
        }
  }
  
  public function executeMarche(sfWebRequest $request)
  {
        $this->vrac = $this->getRoute()->getVrac();
        $this->form = new VracMarcheForm($this->vrac);
        if ($request->isMethod(sfWebRequest::POST)) 
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid())
            {
                $this->form->save();      
                $this->redirect('vrac_condition', $this->vrac);
            }
        }
  }

  public function executeCondition(sfWebRequest $request)
  {
      $this->vrac = $this->getRoute()->getVrac();
      $this->form = new VracConditionForm($this->vrac);
        if ($request->isMethod(sfWebRequest::POST)) 
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid())
            {
                $this->form->save();      
                $this->redirect('vrac_validation', $this->vrac);
            }
        }
  }

   public function executeValidation(sfWebRequest $request)
  {
      $this->vrac = $this->getRoute()->getVrac();
      $this->form = new VracValidationForm($this->vrac);
//        if ($request->isMethod(sfWebRequest::POST)) 
//        {
//            $this->form->bind($request->getParameter($this->form->getName()));
//            if ($this->form->isValid())
//            {
//                $this->form->save();      
//                $this->redirect('vrac_validation', $this->vrac);
//            }
//        }
  }

  protected function init() {
        $this->form = null;
        $this->drm = $this->getRoute();
//        $this->config_lieu = $this->getRoute()->getConfigLieu();
//        $this->drm_lieu = $this->getRoute()->getDrmLieu();
//        $this->produits = $this->drm_lieu->getProduits();
//        $this->previous = $this->drm_lieu->getPreviousSisterWithMouvementCheck();
//        $this->next = $this->drm_lieu->getNextSisterWithMouvementCheck();
    }  
}
