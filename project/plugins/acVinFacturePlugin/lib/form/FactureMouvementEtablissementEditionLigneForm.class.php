<?php

class FactureMouvementEtablissementEditionLigneForm extends acCouchdbObjectForm {

    protected $interpro_id;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
       $this->interpro_id = $options['interpro_id'];
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        $this->getObject()->add('nouvelle');
        foreach ($this->getObject() as $keyMvt => $mouvement) {
            $this->embedForm($keyMvt, new FactureMouvementEditionLigneForm($mouvement,array('interpro_id' => $this->interpro_id,'key' => $keyMvt)));
        }
        $this->widgetSchema->setNameFormat('facture_mouvement_etablissement_edition_ligne[%s]');
    }    
  
    
}
