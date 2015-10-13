<?php
/**
 * Model for TemplateFacture
 *
 */

class TemplateFacture extends BaseTemplateFacture 
{
	
	public function generateCotisations($identifiant_or_compte, $campagne, $force = false)
	{
		$template = $this;
		$compte = $identifiant_or_compte;
		
		if(is_string($compte)) {
			$compte = CompteClient::getInstance()->findByIdentifiant($identifiant_or_compte);
		}
		
		$cotisations = array();
		foreach ($this->docs as $doc) {
			$document = $this->getDocumentFacturable($doc, $compte->identifiant, $campagne);
			if(!$document) {

				throw new sfException(sprintf("Le document %s n'a pas été trouvé (%s-%s-%s)", strtoupper($doc), strtoupper($doc), $compte->identifiant, $campagne));
			}

			if(!count($document->mouvements)) {
				$document->generateMouvements();
				$document->save();
			}

			if($document->isFactures() && !$force) {
				continue;
			}

			foreach ($this->cotisations as $key => $cotisation) {
				
				$modele = $cotisation->modele;

				$object = new $modele($compte, $cotisation->callback);
				$details = $object->getDetails($cotisation->details);
				
				if (!in_array($cotisation->libelle, array_keys($cotisations))) {
					$cotisations[$key] = array();
					$cotisations[$key]["libelle"] = $cotisation->libelle;
					$cotisations[$key]["code_comptable"] = $cotisation->code_comptable;
					$cotisations[$key]["details"] = array();
					$cotisations[$key]["origines"] = array();
				}
				foreach ($details as $type => $detail) {
					$docs = $detail->docs->toArray();
					if (in_array($document->type, $docs)) {
						$modele = $detail->modele;
						$object = new $modele($template, $document, $detail);

						if ($key == 'syndicat_viticole') {
							$cotisations[$key]["details"][] = array("libelle" => $object->getLibelle(), "taux" => $detail->tva, "prix" => $object->getTotal(), "total" => $object->getTotal(), "tva" => $object->getTva(), "quantite" => 1);
						} else {
							$cotisations[$key]["details"][] = array("libelle" => $object->getLibelle(), "taux" => $detail->tva, "prix" => $object->getPrix(), "total" => $object->getTotal(), "tva" => $object->getTva(), "quantite" => $object->getQuantite());
						}
						$cotisations[$key]["origines"][$document->_id] = array($this->_id);
					}
				}
			}
		}
		return $cotisations;
	}
	
	public function getDocumentFacturable($docModele, $identifiant, $campagne)
	{
		$client = acCouchdbManager::getClient($docModele);
		if ($client instanceof FacturableClient) {

			return $client->findFacturable($identifiant, $campagne);
		}
		throw new sfException($docModele.'Client must implements FacturableClient interface');
	}

}