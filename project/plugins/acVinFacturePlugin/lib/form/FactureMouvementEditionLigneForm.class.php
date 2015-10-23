<?php

class FactureMouvementEditionLigneForm extends acCouchdbObjectForm {

    protected $interpro_id;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
       $this->interpro_id = $options['interpro_id'];
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->setWidget("identifiant_analytique", new sfWidgetFormInput());
        $this->setWidget('identifiant', new WidgetSociete(array('interpro_id' => $this->interpro_id)));
        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setWidget("quantite", new sfWidgetFormInputFloat());
        $this->setWidget("prix_unitaire", new sfWidgetFormInputFloat());




        $this->setValidator('identifiant', new ValidatorSociete(array('required' => true)));
        $this->setValidator("identifiant_analytique", new sfValidatorString(array('required' => true)));
        $this->setValidator("libelle", new sfValidatorString(array('required' => true)));
        $this->setValidator("quantite", new sfValidatorNumber(array('required' => true)));
        $this->setValidator("prix_unitaire", new sfValidatorNumber(array('required' => true)));

        $this->validatorSchema['identifiant']->setMessage('required', 'Le choix d\'une societe est obligatoire');

//        $this->validatorSchema->setPreValidator(new FactureEditionLigneValidator());

        $this->configureTypeSociete(array(SocieteClient::SUB_TYPE_VITICULTEUR, SocieteClient::SUB_TYPE_NEGOCIANT));
        $this->widgetSchema->setNameFormat('facture_mouvment_edition_ligne[%s]');
    }
    
       public function configureTypeSociete($types) {
        $this->getWidget('identifiant')->setOption('type_societe', $types);
        $this->getValidator('identifiant')->setOption('type_societe', $types);
    }

    public function getSociete() {
        return $this->getValidator('identifiant')->getDocument();
    }
    
}
