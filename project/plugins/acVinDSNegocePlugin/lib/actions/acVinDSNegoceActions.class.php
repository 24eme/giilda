<?php
class acVinDSNegoceActions extends sfActions
{
	public function executeMonEspace(sfWebRequest $request) {
		$this->etablissement = $this->getRoute()->getEtablissement();
		$this->date = $request->getParameter('periode', date('Y-m-d'));

		$this->dsnegoce = DSNegoceClient::getInstance()->createOrFind($this->etablissement->identifiant, $this->date);

		$this->formPeriodes = new DSNegoceEtablissementPeriodesForm($this->etablissement->identifiant, $this->date);

		if ($request->isMethod(sfWebRequest::POST)) {
			$param = $request->getParameter($this->formPeriodes->getName());
			if ($param) {
				$this->formPeriodes->bind($param);
				return $this->redirect('dsnegoce_etablissement', array('identifiant' => $this->etablissement->getIdentifiant(), 'periode' => $this->formPeriodes->getValue('periode')));
			}
		}
	}
}
