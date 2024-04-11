<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CompteGroupeAjoutForm extends baseForm {


  	public function __construct($interpro_id, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro_id = $interpro_id;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}

    public function configure()
    {

      $this->setWidget('id_compte', new WidgetCompte(array('interpro_id' => $this->interpro_id)));
      $this->widgetSchema->setLabel('id_compte', 'Compte');
      $this->setValidator('id_compte', new ValidatorCompte(array('required' => true)));
      $this->validatorSchema['id_compte']->setMessage('required', 'Le choix d\'un compte est obligatoire');

      $this->setWidget('fonction', new bsWidgetFormInput());
      $this->widgetSchema->setLabel('fonction', 'Fonction');
      $this->setValidator('fonction', new sfValidatorString(array('required' => true)));

      $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
      $this->widgetSchema->setNameFormat('compte_groupe_ajout[%s]');
    }


    public function getFonctionsForAutocomplete(){
      $q = new acElasticaQuery();
      $elasticaFacet   = new acElasticaFacetTerms('groupes');
      $elasticaFacet->setField('doc.groupes.fonction');
      $elasticaFacet->setSize(250);
      $q->addFacet($elasticaFacet);

      $index = acElasticaManager::getType('COMPTE');
      $resset = $index->search($q);
      $results = $resset->getResults();
      $this->facets = $resset->getFacets();

      ksort($this->facets);
      $entries = array();
      foreach ($this->facets["groupes"]["buckets"] as $facet) {
          if($facet["key"]){
            $entry = new stdClass();
            $entry->id = trim($facet["key"]);
            $entry->text = trim($facet["key"]);
            $entries[] = $entry;
        }
      }

      return $entries;
    }

}
