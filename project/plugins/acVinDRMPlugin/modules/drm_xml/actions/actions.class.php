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

  public function executeTransfert(sfWebRequest $request) {
    $this->drm = $this->getRoute()->getDRM();
    $this->etablissement = $this->getRoute()->getEtablissement();
    if ($request->isMethod(sfWebRequest::POST)) {
      if (!CielService::hasAppConfig() ||
          ($this->drm->add('transmission_douane')->exist('success') && $this->drm->add('transmission_douane')->get('success'))) {
        return $this->redirect('drm_ciel', $this->drm);
      }
      if ($xml = $this->getPartial('xml', array('drm' => $this->drm))) {
        $service = new CielService();
        $service->transferAndStore($this->drm, $xml);
        if (!$this->drm->transmission_douane->success) {
          $to = sfConfig::get('app_ac_exception_notifier_email');
          $to = ($to && isset($to->to)) ? $to->to : 'vins@actualys.com';
          $msg = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')),
          $to,
          "Erreur transmision XML pour ".$this->drm->_id,
          "Une transmission vient d'échouer pour la DRM ".$this->drm->_id." : \n".$this->drm->transmission_douane->xml);
          $this->getMailer()->send($msg);
        }
        return $this->redirect('drm_ciel', $this->drm);
      }
    }
    $this->cielResponse = $this->drm->transmission_douane->xml;
  }

  public function executePrint(sfWebRequest $request) {
      $this->drm = $this->getRoute()->getDrm();
      $this->setLayout(false);
      $this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
  }

  public function executeMain()
  {
  }

}
