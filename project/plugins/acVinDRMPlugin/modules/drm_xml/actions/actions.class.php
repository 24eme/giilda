<?php

/**
* Description of actions
*
*/
class drm_xmlActions extends drmGeneriqueActions {

  public function executeWait(sfWebRequest $request) {
    $this->setLayout(false);
    $this->drm = $this->getRoute()->getDRM();
  }

  public function executeRetransmission(sfWebRequest $request) {
    $this->setLayout(false);
    $this->drm = $this->getRoute()->getDRM();
    $this->drm->remove('transmission_douane');
    $this->drm->save();
    return $this->redirect('drm_transmission',$this->drm);
  }

  public function executeTransfert(sfWebRequest $request) {
    $this->drm = $this->getRoute()->getDRM();
    $this->etablissement = $this->getRoute()->getEtablissement();
    if ($request->isMethod(sfWebRequest::POST)) {
      if (!CielService::hasAppConfig() ||
          ($this->drm->add('transmission_douane')->exist('success') && $this->drm->add('transmission_douane')->get('success'))) {
        return $this->redirect('drm_ciel', $this->drm);
      }

      $this->drm->transferToCiel();

      if (!$this->drm->transmission_douane->success) {
          $to = sfConfig::get('app_ac_exception_notifier_email');
          $to = ($to && isset($to->to)) ? $to->to : 'logs@24eme.fr';
          $msg = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')),
          $to,
          "Erreur transmision XML pour ".$this->drm->_id,
          "Une transmission vient d'échouer pour la DRM ".$this->drm->_id." : \n".$this->drm->transmission_douane->xml);
          $this->getMailer()->send($msg);
      }

      return $this->redirect('drm_ciel', $this->drm);
    }
    $this->cielResponse = null;
    if($this->drm->exist('transmission_douane')) {
        $this->cielResponse = $this->drm->transmission_douane->xml;
    }
  }

  public function executePrint(sfWebRequest $request) {
      $this->drm = $this->getRoute()->getDrm();
      $this->setLayout(false);
      $this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
  }

  public function executeRetour(sfWebRequest $request) {
    $this->drm = $this->getRoute()->getDRM();
    $this->setLayout(false);
    $this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
  }

  public function executeRetourRefresh(sfWebRequest $request) {
    $this->drm = $this->getRoute()->getDRM();
    $this->setLayout(false);
    $pathScript = realpath('../bin/updateOneDouaneDrmComparaison.sh');
    if(!$pathScript){
        throw new sfException("Le script de mis à jour n'existe pas");
    }
    $dateRequete = ConfigurationClient::getInstance()->buildDate(ConfigurationClient::getInstance()->getPeriodePrecedente($this->drm->periode));
    $periode = $this->drm->periode;
    $etb = $this->drm->getEtablissement();
    $numeroAccise = $etb->getNoAccises();
    $cvi = $etb->getCvi();
    $cmd = 'bash '.$pathScript." \"".$numeroAccise."\" \"".$periode."\" \"".$cvi."\" \"".$dateRequete."\"";
    $retour = shell_exec($cmd);
    if(!$this->drm->isValidee()){
        return $this->redirect('drm_validation', $this->drm);
    }
    //Si l'algo du diff a changé, le coherente n'a pas changé alors qu'il devrait.
    //Si on ne recharge pas la DRM, il y a un update conflict
    $this->drm = DRMClient::getInstance()->find($this->drm->_id);
    if ($this->drm->exist('transmission_douane/coherente') && $this->drm->exist('transmission_douane/success') && $this->drm->transmission_douane->success && !$this->drm->transmission_douane->coherente && $this->drm->areXMLIdentical()) {
        $this->drm->getOrAdd('transmission_douane')->add("coherente", true);
        $this->drm->save();
    }

    return $this->redirect('drm_visualisation', $this->drm);
  }

  public function executeTable(sfWebRequest $request){
      $this->drm = $this->getRoute()->getDRM();
      $this->retour = $request->getParameter('retour',null);
      $this->xml = $this->drm->getXML();

      if($this->retour && html_entity_decode($this->drm->getXMLRetour())){
          $this->xml = html_entity_decode($this->drm->getXMLRetour());
      }

      $compare = new DRMCielCompare($this->xml, null);
      $this->xml_table = $compare->xmlInToArray();
  }


  public function executeMain()
  {
  }

  public function executeSuccessTrue(sfWebRequest $request) {
      $drm = $this->getRoute()->getDRM();
      $drm->initTransmission();
      $drm->transmission_douane->success = true;
      $drm->save();

      return $this->redirect('drm_visualisation', $drm);
  }

  public function executeRetourIgnore(sfWebRequest $request) {
      $drm = $this->getRoute()->getDRM();
      if ($drm->exist('transmission_douane')) {
          $drm->transmission_douane->coherente = true;
          $drm->save();
      }
      return $this->redirect('drm_redirect_etape', $drm);
  }

}
