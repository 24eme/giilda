<?php

class mandatsepaActions extends sfActions
{

	public function executePdf(sfWebRequest $request) {
        if (MandatSepaConfiguration::getInstance()->isAccessibleTeledeclaration() === false) {
            return $this->redirect403();
        }
		$mandatSepa = $this->getRoute()->getMandatSepa();
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

        if (! $this->getUser()->isAdmin()) {
            if (MandatSepaConfiguration::getInstance()->isAccessibleTeledeclaration() === false) {
                return $this->redirect403();
            }

            if (MandatSepaConfiguration::getInstance()->isAccessibleTeledeclaration() === true && $this->getUser()->getCompte()->getSociete()->getIdentifiant() !== $request->getParameter('identifiant')) {
                return $this->redirect403();
            }
        }

        $mandatSepa = MandatSepaClient::getInstance()->findLastBySociete($this->societe);
        if (!$mandatSepa) {
            $mandatSepa = MandatSepaClient::getInstance()->createDoc($this->societe);
        }
        $this->form = new MandatSepaDebiteurForm($mandatSepa->debiteur);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->getUser()->setFlash('maj', 'Vos coordonnées bancaires ont bien été mises à jour.');
                $this->redirect('societe_visualisation', ['identifiant' => $this->societe->getIdentifiant()]);
            }
        }
    }
}
