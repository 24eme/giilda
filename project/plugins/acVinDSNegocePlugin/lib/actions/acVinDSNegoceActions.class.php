<?php
class acVinDSNegoceActions extends sfActions
{
	public function executeMonEspace(sfWebRequest $request) {
		$this->etablissement = $this->getRoute()->getEtablissement();
		$this->date = DSNegoceClient::getDateDeclaration($request->getParameter('date', date('Y-m-d')));

		$this->dsnegoce = DSNegoceClient::getInstance()->find(DSNegoceClient::makeId($this->etablissement->identifiant, $this->date));
		$this->docRepriseProduits = DSNegoceClient::getDocumentRepriseProduits($this->etablissement->identifiant, $this->date);

		$this->formPeriodes = new DSNegoceEtablissementPeriodesForm($this->etablissement->identifiant, $this->date);

		if ($request->isMethod(sfWebRequest::POST)) {
			$param = $request->getParameter($this->formPeriodes->getName());
			if ($param) {
				$this->formPeriodes->bind($param);
				return $this->redirect('dsnegoce_etablissement', array('identifiant' => $this->etablissement->getIdentifiant(), 'date' => $this->formPeriodes->getValue('date')));
			}
		}
	}
}
