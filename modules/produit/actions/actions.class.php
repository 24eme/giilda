<?php

/**
 * produit actions.
 *
 * @package    declarvin
 * @subpackage produit
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class produitActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	//$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
	  $this->produits = ConfigurationClient::getCurrent()->declaration->getProduitsWithoutView();
  }
  public function executeModification(sfWebRequest $request)
  {
  	$this->forward404Unless($noeud = $request->getParameter('noeud', null));
  	$this->forward404Unless($hash = $request->getParameter('hash', null));
  	$this->nbDepartement = $request->getParameter('nb_departement', null);
  	$this->nbDouane = $request->getParameter('nb_douane', null);
  	$this->nbCvo = $request->getParameter('nb_cvo', null);
  	$this->nbLabel = $request->getParameter('nb_label', null);
  	$this->interpro = 'INTERPRO-inter-loire';
  	$hash = str_replace('-', '/', $hash);
  	$object = ConfigurationClient::getCurrent()->getOrAdd($hash);
    $object = $object->get($noeud);

  	if ($pile = $this->getUser()->hasAttribute('pile_noeud') && !$request->isMethod(sfWebRequest::POST)) {
  		$pile = $this->getUser()->getAttribute('pile_noeud');
  		$arborescence = ConfigurationProduit::getArborescence();
  		$nextNoeuds = false;
  		foreach ($arborescence as $produit) {
  			if ($nextNoeuds) {
	  			if (isset($pile[$produit])) {
	  				$object = $object->getOrAdd($produit)->add(Configuration::DEFAULT_KEY);
	  				$object->set('libelle', $pile[$produit]);
	  				$noeud = $object->getTypeNoeud();
	  				if ($noeud == ConfigurationCouleur::TYPE_NOEUD) {
	  					$codeCouleurs = ConfigurationProduit::getCodeCouleurs();
	  					$object->set('code', $codeCouleurs[$pile[$produit]]);
	  				}
	  				break;
	  			} else {
	  				$object = $object->getOrAdd($produit)->add(Configuration::DEFAULT_KEY);
	  			}
  			}
	  		if (preg_match('/^'.$object->getTypeNoeud().'[a-z]?$/', $produit)) {
	  			$nextNoeuds = true;
	  		}
  		}
  	}
  	$this->noeud = $noeud;
  	$this->form = new ProduitDefinitionForm($object, array('nbDepartement' => $this->nbDepartement, 'nbDouane' => $this->nbDouane, 'nbCvo' => $this->nbCvo, 'nbLabel' => $this->nbLabel));
  	$this->form->setHash($hash);
  	
  	if ($request->isMethod(sfWebRequest::POST)) {
        $this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$object = $this->form->save();
			$this->getUser()->setFlash("notice", 'Le produit a été modifié avec success.');
			if ($pile = $this->getUser()->hasAttribute('pile_noeud')) {
  				$pile = $this->getUser()->getAttribute('pile_noeud');
		  		$arborescence = ConfigurationProduit::getArborescence();
		  		foreach ($arborescence as $produit) {
		  			if (isset($pile[$produit])) {
		  				unset($pile[$produit]);
		  				if (count($pile) > 0) {
		  					$this->getUser()->setAttribute('pile_noeud', $pile);
		  					$this->redirect('produit_modification', array('noeud' => $object->getTypeNoeud(), 'hash' => str_replace('/', '-', $this->getNextHash($object))));
		  				} else {
		  					$this->getUser()->setAttribute('pile_noeud', null);
		  					$this->redirect('produits');
		  				}
		  			}
		  		}
		  	}
		  	$this->redirect('produits');
		}
    }
  }
  public function executeSuppression(sfWebRequest $request)
  {
  	$this->forward404Unless($hash = $request->getParameter('hash', null));
  	$this->nbDepartement = $request->getParameter('nb_departement', null);
  	$this->nbDouane = $request->getParameter('nb_douane', null);
  	$this->nbCvo = $request->getParameter('nb_cvo', null);
  	$this->nbLabel = $request->getParameter('nb_label', null);
  	$hash = str_replace('-', '/', $hash);
  	$object = ConfigurationClient::getCurrent()->getOrAdd($hash);
  	while ($object->getParent()->count() == 1) {
  		$object = $object->getParentNode();
  	}
  	$doc = $object->getDocument();
  	$object->delete();
  	$doc->save();
	$this->redirect('produits');
  }
  private function getNextHash($object) {
  	$hash = $object->getHash();
  	$nodes = ConfigurationProduit::getArborescence();
  	$noeud = $object->getTypeNoeud();
  	$nextNoeuds = false;
  	foreach ($nodes as $node) {
  		if ($nextNoeuds) {
  			$hash = $hash.'/'.$node.'/'.Configuration::DEFAULT_KEY;
  		}
  		if (preg_match('/^'.$noeud.'[a-z]?$/', $node)) {
  			$nextNoeuds = true;
  		}
  	}
  	return $hash;
  }
  public function executeNouveau(sfWebRequest $request)
  {
  	$this->interpro = InterproClient::getInstance()->find('INTERPRO-inter-loire');
  	$configuration = ConfigurationClient::getCurrent();
  	$this->form = new ProduitNouveauForm($configuration, $this->interpro->_id);
  	if ($request->isMethod(sfWebRequest::POST)) {
        $this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$result = $this->form->save();
			$hash = $result['hash'];
			$noeud = $result['noeud'];
			$values = $result['values'];
			if ($values) {
				$this->getUser()->setAttribute('pile_noeud', $values);
			}
			$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec success.');
			$this->redirect('produit_modification', array('noeud' => $noeud, 'hash' => str_replace('/', '-', $hash)));
		}
    }
  }
}
