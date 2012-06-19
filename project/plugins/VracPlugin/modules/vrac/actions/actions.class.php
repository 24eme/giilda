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
      $this->vracs = VracClient::getInstance()->retrieveLastDocs();
  }

  public function executeRechercheSoussigne(sfWebRequest $request) {
      $this->identifiant = $request->getParameter('identifiant');
      $soussigne = 'ETABLISSEMENT-'.$this->identifiant;
      $this->vracs = VracClient::getInstance()->retrieveBySoussigne($soussigne);
  }
  
  public function executeNouveau(sfWebRequest $request)
  {      
      $this->getResponse()->setTitle('Contrat - Nouveau');
      $this->vrac = new Vrac();
      $this->form = new VracSoussigneForm($this->vrac);
 
      $this->init_soussigne($request,$this->form);
      
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
  
  
  private function init_soussigne($request,$form)
    {
        $form->vendeur = null;
        $form->acheteur = null;  
        $form->mandataire = null;  

        if(!is_null($request->getParameter('vrac')) && !$request->getParameter('vrac')=='')
        {
            $vracParam = $request->getParameter('vrac');

            if(!is_null($vracParam['vendeur_identifiant']) && !empty($vracParam['vendeur_identifiant']))
            { 
                $form->vendeur = EtablissementClient::getInstance()->find($vracParam['vendeur_identifiant']);
            }
            if(!is_null($vracParam['acheteur_identifiant']) && !empty($vracParam['acheteur_identifiant']))
            { 
                $form->acheteur = EtablissementClient::getInstance()->find($vracParam['acheteur_identifiant']);
            }
            if(!is_null($vracParam['mandataire_identifiant']) && !empty($vracParam['mandataire_identifiant']))
            { 
                $form->mandataire = EtablissementClient::getInstance()->find($vracParam['mandataire_identifiant']);
            }
        }
    }
  
  public function executeSoussigne(sfWebRequest $request)
  {
      $this->getResponse()->setTitle(sprintf('Contrat N° %d - Soussignés', $request["numero_contrat"]));
      $this->vrac = $this->getRoute()->getVrac();
      $this->form = new VracSoussigneForm($this->vrac);
      
      $this->init_soussigne($request,$this->form);
      
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
        $this->getResponse()->setTitle(sprintf('Contrat N° %d - Marché', $request["numero_contrat"]));
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
      $this->getResponse()->setTitle(sprintf('Contrat N° %d - Conditions', $request["numero_contrat"]));
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
      $this->getResponse()->setTitle(sprintf('Contrat N° %d - Validation', $request["numero_contrat"]));
      $this->vrac = $this->getRoute()->getVrac();
        if ($request->isMethod(sfWebRequest::POST)) 
        {
            $this->maj_etape(4);
            $this->maj_valide(null,null,VracClient::STATUS_CONTRAT_NONSOLDE);
            $this->vrac->save();
            $this->redirect('vrac_termine', $this->vrac);
        }
  }
  
  
  public function executeRecapitulatif(sfWebRequest $request)
  {
      $this->getResponse()->setTitle(sprintf('Contrat N° %d - Récapitulation', $request["numero_contrat"]));
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

    public function maj_valide($date_saisie = null,$identifiant = null,$status=null)
    {
        if(!$this->vrac) return;
        if(!$date_saisie) $date_saisie = date('d/m/Y');
        $this->vrac->valide->date_saisie = $date_saisie;
        $this->vrac->valide->identifiant = $identifiant;
        $this->vrac->valide->status = $status;
    }
  
}
