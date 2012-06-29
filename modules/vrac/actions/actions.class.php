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

  static function rechercheTriListOnID($etb0, $etb1)
    {
        if ($etb0->id == $etb1->id) {
            return 0;
        }
        return ($etb0->id > $etb1->id) ? -1 : +1;
    }
      
  
  public function executeRecherche(sfWebRequest $request) 
  {            
      $this->getVracsFromRecherche($request,true);
  }
   
  private function getVracsFromRecherche($request, $limited)
  {
      $isType = isset($request['type']);
      $isStatut = isset($request['statut']);
      $this->identifiant = $request->getParameter('identifiant');
      $soussigneObj = EtablissementClient::getInstance()->findByIdentifiant($this->identifiant);
      $soussigneId = 'ETABLISSEMENT-'.$this->identifiant;
     
      if($isStatut)
      {
          $this->statut = $request['statut'];
          $this->vracs = ($limited)? 
                            VracClient::getInstance()->retrieveBySoussigneAndStatut($soussigneId,$request['statut'])
                            : VracClient::getInstance()->retrieveBySoussigneAndStatut($soussigneId,$request['statut'],false);
          $this->actif = $request['statut'];
      }
      elseif ($isType)
      {
          $this->type = $request['type'];
          $this->vracs = ($limited)? 
                            VracClient::getInstance()->retrieveBySoussigneAndType($soussigneId,$request['type'])
                            : VracClient::getInstance()->retrieveBySoussigneAndType($soussigneId,$request['type'],false);
          $this->actif = $request['type'];
      }
      else
      {          
          $this->vracs = ($limited)? 
                            VracClient::getInstance()->retrieveBySoussigne($soussigneId)
                            : VracClient::getInstance()->retrieveBySoussigne($soussigneId,false);
          $this->actif = null;
      }
      
      
      usort($this->vracs->rows, array("vracActions", "rechercheTriListOnID"));
      
      
      $this->etablissements = array('' => '');
      $soussignelabel = array($soussigneObj['nom'], $soussigneObj['cvi'], $soussigneObj['commune']);
      $this->etablissements[$this->identifiant] = trim(implode(',', array_filter($soussignelabel)));

      $datas = EtablissementClient::getInstance()->findAll()->rows;

      foreach($datas as $data) 
        {
                $labels = array($data->key[4], $data->key[3], $data->key[1]);
                $this->etablissements[$data->id] = trim(implode(',', array_filter($labels)));
        }
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
  
  public function executeGetContratsSimilaires(sfWebRequest $param)
  {
       $vrac = VracClient::getInstance()->findByNumContrat($param['numero_contrat']);
       
       switch ($param['etape']) {
           case 'soussigne':
           {
               $vrac[VracClient::VRAC_SIMILAIRE_KEY_VENDEURID] = $param['vendeur'];
               $vrac[VracClient::VRAC_SIMILAIRE_KEY_ACHETEURID] = $param['acheteur'];
               $vrac[VracClient::VRAC_SIMILAIRE_KEY_MANDATAIREID] = $param['mandataire'];   
               return $this->renderPartial('contratsSimilaires', array('vrac' => $vrac));
           }           
           case 'marche':
           {
               $vrac[VracClient::VRAC_SIMILAIRE_KEY_PRODUIT] = $param['produit'];
               return $this->renderPartial('contratsSimilaires', array('vrac' => $vrac));
           }
           default:
           {
               echo 'Une erreur est survenue lors du chargement des contrats similaire';               
               return;
           }
       }
            
  }

  public function executeVolumeEnleve(sfWebRequest $request)
  {
        $this->vrac = VracClient::getInstance()->findByNumContrat($request['numero_contrat']);

        $this->form = new VracVolumeEnleveForm($this->vrac);
        if ($request->isMethod(sfWebRequest::POST)) 
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid())
            {
                $this->form->save();      
                $this->redirect('vrac_termine', $this->vrac);
            }
        }
  }
  
  public function executeExportCsv(sfWebRequest $request) 
  {
    $this->setLayout(false);
    $this->response->setContentType('text/csv');
    $this->getVracsFromRecherche($request, false);
    
    $this->forward404Unless($this->vracs);
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

    public function maj_valide($date_saisie = null,$identifiant = null,$statut=null)
    {
        if(!$this->vrac) return;
        if(!$date_saisie) $date_saisie = date('d/m/Y');
        $this->vrac->valide->date_saisie = $date_saisie;
        $this->vrac->valide->identifiant = $identifiant;
        $this->vrac->valide->statut = $statut;
    }
  
}
