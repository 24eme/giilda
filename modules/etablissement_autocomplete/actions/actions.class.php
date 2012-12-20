<?php

class etablissement_autocompleteActions extends sfActions
{

  	public function executeAll(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
	    $json = $this->matchEtablissements(EtablissementAllView::getInstance()->findByInterproAndStatut($interpro, EtablissementClient::STATUT_ACTIF)->rows,
	    								   $request->getParameter('q'),
	    								   $request->getParameter('limit', 100));

	    return $this->renderText(json_encode($json));
  	}

 	public function executeByFamilles(sfWebRequest $request) {
	    $interpro = $request->getParameter('interpro_id');
		$familles = $request->getParameter('familles');
		
	    $json = $this->matchEtablissements(
	    	EtablissementAllView::getInstance()->findByInterproStatutAndFamilles($interpro, EtablissementClient::STATUT_ACTIF, explode('|', $familles)),
		    $request->getParameter('q'),
		   	$request->getParameter('limit', 100)
		);
	    
 		return $this->renderText(json_encode($json));	
  	}

    protected function matchEtablissements($etablissements, $term, $limit) {
    	$json = array();

	  	foreach($etablissements as $key => $etablissement) {
	      $text = EtablissementAllView::getInstance()->makeLibelle($etablissement);
	     
	      if (Search::matchTerm($term, $text)) {
	        $json[EtablissementClient::getInstance()->getId($etablissement->id)] = $text;
	      }

	      if (count($json) >= $limit) {
	        break;
	      }
	    }
	    return $json;
	}

}
