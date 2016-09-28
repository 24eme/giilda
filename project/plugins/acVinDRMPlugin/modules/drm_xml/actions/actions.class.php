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
      $this->cielResponse = '';
      if ($xml = $this->getPartial('xml', array('drm' => $this->drm))) {
        try {
          $service = new CielService();
          $this->cielResponse = $service->transfer($xml);
        } catch (sfException $e) {
          $this->cielResponse = $e->getMessage();
        }
      } else {
        $this->cielResponse = "Une erreur est survenue à la génération du XML.";
      }
      $this->drm->add('transmission_douane')->add('xml', $this->cielResponse);
      $this->drm->add('transmission_douane')->add('success', false);
      if (preg_match('/identifiant-declaration>([^<]*)<.*horodatage-depot>([^<]+)</', $this->cielResponse, $m)) {
        $this->drm->add('transmission_douane')->add('success', true);
        $this->drm->add('transmission_douane')->add('horodatage', $m[2]);
        $this->drm->add('transmission_douane')->add('id_declaration', $m[1]);
      }
      $this->drm->save();
      return $this->redirect('drm_ciel', $this->drm);
    }
    $this->cielResponse = $this->drm->transmission_douane->xml;
  }

  public function executePrint(sfWebRequest $request) {
      $this->drm = $this->getRoute()->getDrm();
      $this->setLayout(false);
      $this->getResponse()->setHttpHeader('Content-Type', 'text/xml');
  }

  }
