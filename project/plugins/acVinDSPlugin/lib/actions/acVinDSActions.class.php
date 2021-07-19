<?php
class acVinDSActions extends sfActions
{
	public function executeMonEspace(sfWebRequest $request) {
		$this->etablissement = $this->getRoute()->getEtablissement();
		$this->date = DSClient::getDateDeclaration($request->getParameter('date', date('Y-m-d')));

		$this->ds = DSClient::getInstance()->findMasterByIdentifiantAndDate($this->etablissement->identifiant, $this->date);
		$this->docRepriseProduits = DSClient::getDocumentRepriseProduits($this->etablissement->identifiant, $this->date);

		$this->formPeriodes = new DSEtablissementPeriodesForm($this->etablissement->identifiant, $this->date);

		if ($request->isMethod(sfWebRequest::POST)) {
			$param = $request->getParameter($this->formPeriodes->getName());
			if ($param) {
				$this->formPeriodes->bind($param);
				return $this->redirect('ds_etablissement', array('identifiant' => $this->etablissement->getIdentifiant(), 'date' => $this->formPeriodes->getValue('date')));
			}
		}
	}

	public function executeCreation(sfWebRequest $request) {
			$etablissement = $this->getRoute()->getEtablissement();
			$date = $request->getParameter('date');
			$ds = DSClient::getInstance()->createOrFind($etablissement->identifiant, $date, $this->isTeledeclarationDS());
			if($ds->isNew()) {
				$ds->save();
			}
			return $this->redirect('ds_infos', $ds);
	}

	public function executeInfos(sfWebRequest $request) {
			$this->ds = $this->getRoute()->getDS();
			$this->etablissement = $this->ds->getEtablissementObject();
			$this->isTeledeclarationMode = $this->isTeledeclarationDS();
	}

	public function executeStocks(sfWebRequest $request) {
			$this->ds = $this->getRoute()->getDS();
			$this->etablissement = $this->ds->getEtablissementObject();
			$this->isTeledeclarationMode = $this->isTeledeclarationDS();

			$this->form = new DSProduitsForm($this->ds);

			if (!$request->isMethod(sfWebRequest::POST)) {
					return sfView::SUCCESS;
			}

			$this->form->bind($request->getParameter($this->form->getName()));

			if (!$this->form->isValid()) {
				return sfView::SUCCESS;
			}
			$this->form->save();

			$this->redirect('ds_validation', $this->ds);

	}

	public function executeValidation(sfWebRequest $request) {
			$this->ds = $this->getRoute()->getDS();
			$this->etablissement = $this->ds->getEtablissementObject();
			$this->isTeledeclarationMode = $this->isTeledeclarationDS();
	}

	public function executeValidate(sfWebRequest $request) {
			$this->ds = $this->getRoute()->getDS();
			$this->forward404Unless($this->ds->isValidable());
			$this->ds->validate();
			$this->ds->save();
			$this->redirect('ds_visualisation', $this->ds);
	}

	public function executeDevalidate(sfWebRequest $request) {
			$this->ds = $this->getRoute()->getDS();
			$this->ds->devalidate();
			$this->ds->save();
			$this->redirect('ds_stocks', $this->ds);
	}

	public function executeVisualisation(sfWebRequest $request) {
			$this->ds = $this->getRoute()->getDS();
			$this->etablissement = $this->ds->getEtablissementObject();
			$this->isTeledeclarationMode = $this->isTeledeclarationDS();
	}

	protected function isTeledeclarationDS() {
		return $this->getUser()->hasTeledeclaration();
	}


	public function executeRectifier(sfWebRequest $request) {
			$ds = $this->getRoute()->getDS();

			$rectificative = $ds->generateRectificative();
			$rectificative->save();

			return $this->redirect('ds_stocks', $rectificative);
	}
}
