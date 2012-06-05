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

  public function executeGetInformations(sfWebRequest $request) 
  { 
      $etablissement =  EtablissementClient::getInstance()->find($request->getParameter('id'));
      $nouveau = is_null($request->getParameter('numero_contrat'));
      return $this->renderPartialInformations($etablissement,$nouveau);
  }
  
  public function executeGetModifications(sfWebRequest $request)
  {
        $nouveau = is_null($request->getParameter('numero_contrat'));
        $etablissementId = ($request->getParameter('id')==null)? $request->getParameter('vrac_'.$request->getParameter('type').'_identifiant') : $request->getParameter('id');      
        $etablissement =  EtablissementClient::getInstance()->find($etablissementId);
        $this->form = new VracSoussigneModificationForm($etablissement);
        
        if ($request->isMethod(sfWebRequest::POST)) 
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid())
            {
                $this->form->save();      
                return $this->renderPartialInformations($etablissement,$nouveau);
            }
        }
        
        $familleType = $etablissement->getFamilleType();
        if($familleType == 'vendeur' || $familleType == 'acheteur') $familleType = 'vendeurAcheteur';
        return $this->renderPartial($familleType.'Modification', array('form' => $this->form));
  }
  
  private function renderPartialInformations($etablissement,$nouveau) {
      
      $familleType = $etablissement->getFamilleType();
      return $this->renderPartial($familleType.'Informations', 
        array($familleType => $etablissement, 'nouveau' => $nouveau));
  }
  
  private function maj_etape($etapeNum)
  {
      if($this->vrac->etape < $etapeNum) $this->vrac->etape = $etapeNum;
  }
  
}
