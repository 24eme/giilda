<?php

class etablissement_autocompleteActions extends sfActions
{

  	public function executeAll(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
	    $q = $request->getParameter('q');
	    $limit = $request->getParameter('limit', 500);
	    $e = EtablissementAllView::getInstance()->findByInterproAndStatut($interpro, EtablissementClient::STATUT_ACTIF, $q, $limit);
	    $json = $this->matchEtablissements($e, $q, $limit);
        $this->getResponse()->setContentType('text/json');
	    return $this->renderText(json_encode($json));
  	}

 	public function executeByFamilles(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
		$familles = $request->getParameter('familles');

	    $q = $request->getParameter('q');
	    $limit = $request->getParameter('limit', 100);
	    $json = $this->matchEtablissements(
					       EtablissementAllView::getInstance()->findByInterproStatutAndFamilles($interpro, EtablissementClient::STATUT_ACTIF, explode('|', $familles), $q, $limit),
					       $q,
					       $limit
					       );

 		return $this->renderText(json_encode($json));
  	}

    protected function matchEtablissements($etablissements, $term, $limit) {
    	$json = array();

	  	foreach($etablissements as $key => $etablissement) {
	      $text = EtablissementAllView::getInstance()->makeLibelle($etablissement);
	      if (Search::matchTerm($term, $text)) {
            $compte = CompteClient::getInstance()->find(str_replace("ETABLISSEMENT-", "COMPTE-", $etablissement->id));
            $verif_exploite_plus = $compte->exist('tags') && $compte->tags->exist('manuel') && in_array('exploite_plus', $compte->tags->manuel->toArray(0,1));
            $verif_en_alert = $compte->exist('en_alerte') && $compte->en_alerte;
            if($compte && ($verif_exploite_plus || $verif_en_alert)){
                $text = str_replace('(', ' ⛔ (', $text);
            }
	        $json[EtablissementClient::getInstance()->getId($etablissement->id)] = $text;
	      }

	      if (count($json) >= $limit) {
	        break;
	      }
	    }
	    return $json;
	}

}
