<?php

class mandatsepaActions extends sfActions
{

	public function executePdf(sfWebRequest $request) {
        $mandatSepa = $this->getRoute()->getMandatSepa();
        $configuration = $mandatSepa->getConfiguration();
        if (MandatSepaConfiguration::getInstance()->isAccessibleTeledeclaration() === false) {
            return $this->redirect403();
        }
		$this->document = new MandatSepaPDF($mandatSepa, $request->getParameter('output','pdf'), false);
        $this->document->setPartialFunction(array($this, 'getPartial'));
        if ($request->getParameter('force')) {
            $this->document->removeCache();
        }
        $this->document->generate();
		$output = $this->document->output();
		$mandatSepa->setIsTelecharge(1);
		$mandatSepa->save();
        $this->document->addHeaders($this->getResponse());
        return $this->renderText($output);
	}

    public function executeModification(sfWebRequest $request)
    {
        $this->societe = SocieteClient::getInstance()->find($request->getParameter('identifiant'));
        $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($this->societe);
        if (!$mandatSepa) {
            $mandatSepa = MandatSepaClient::getInstance()->createDoc($this->societe);
        }
        $this->configuration = $mandatSepa->getConfiguration();

        if (! $this->getUser()->isAdmin()) {
            if ($this->configuration->isAccessibleTeledeclaration() === false) {
                $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
            }

            if ($this->configuration->isAccessibleTeledeclaration() === true && $this->getUser()->getCompte()->getSociete()->getIdentifiant() !== $request->getParameter('identifiant')) {
                $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
            }
        }

        $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($this->societe);
        if (!$mandatSepa) {
            $mandatSepa = MandatSepaClient::getInstance()->createDoc($this->societe);
        }
        $this->form = new MandatSepaDebiteurForm($mandatSepa->debiteur, $this->getUser()->isAdmin());
        $this->back = ($this->configuration->getEditBack()) ?: 'societe_visualisation';

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->getUser()->setFlash('maj', 'Vos coordonnées bancaires ont bien été mises à jour.');
                $this->redirect($this->back, ['identifiant' => $this->societe->getIdentifiant()]);
            }
        }
    }

    public function executeMain()
    {
    }
}
