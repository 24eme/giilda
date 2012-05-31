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
    return $this->redirect('vrac/nouveau');
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
                $this->maj_etape(1);
                $this->vrac->numero_contrat = VracClient::getInstance()->getNextNoContrat();
                $this->form->save();      
                return $this->redirect('vrac_marche', $this->vrac);
            }
        }
      $this->setTemplate('soussigne');
  }
  
  public function executeSoussigne(sfWebRequest $request)
  {
      $this->vrac = $this->getRoute()->getVrac();
      $this->form = new VracSoussigneForm($this->vrac);
      if ($request->isMethod(sfWebRequest::POST)) 
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid())
            {
                $this->maj_etape(1);                
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
                $this->maj_etape(2);
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
                $this->maj_etape(3);
                $this->form->save();      
                $this->redirect('vrac_validation', $this->vrac);
            }
        }
  }

   public function executeValidation(sfWebRequest $request)
  {
      $this->vrac = $this->getRoute()->getVrac();
        if ($request->isMethod(sfWebRequest::POST)) 
        {
            $this->maj_etape(4);
            $this->vrac->save();
            $this->redirect('vrac_termine', $this->vrac);
        }
  }
  
  
  public function executeRecapitulatif(sfWebRequest $request)
  {
      $this->vrac = $this->getRoute()->getVrac();
      if ($request->isMethod(sfWebRequest::POST)) 
      {
            $this->redirect('vrac_soussigne');
      }
  }

  public function executeGetVendeurInformations(sfWebRequest $request) 
  { 
      return $this->renderPartial('vendeurInformations', 
              array('vendeur' => EtablissementClient::getInstance()->find($request->getParameter('id'))));
  }
  
  public function executeGetAcheteurInformations(sfWebRequest $request) 
  { 
      return $this->renderPartial('acheteurInformations', 
              array('acheteur' => EtablissementClient::getInstance()->find($request->getParameter('id'))));
  }
  
  public function executeGetMandataireInformations(sfWebRequest $request) 
  { 
      return $this->renderPartial('mandataireInformations', 
              array('mandataire' => EtablissementClient::getInstance()->find($request->getParameter('id'))));
  }

  public function executeGetMandataireModification(sfWebRequest $request)
  {
      return $this->renderPartial('mandataireModifications', 
              array('mandataire' => EtablissementClient::getInstance()->find($request->getParameter('id'))));
  }

  public function executeGetAcheteurModification(sfWebRequest $request)
  {
      return $this->renderPartial('acheteurModifications', 
              array('acheteur' => EtablissementClient::getInstance()->find($request->getParameter('id'))));
  }
  
  public function executeGetVendeurModification(sfWebRequest $request)
  {
      return $this->renderPartial('vendeurModification', 
              array('vendeur' => EtablissementClient::getInstance()->find($request->getParameter('id'))));
  }
  
  private function maj_etape($num_etape)
  {
      if($num_etape > $this->vrac->etape) $this->vrac->etape = $num_etape;
  }

 
}
