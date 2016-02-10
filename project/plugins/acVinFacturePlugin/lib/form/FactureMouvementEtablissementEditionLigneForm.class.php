<?php

class FactureMouvementEtablissementEditionLigneForm extends acCouchdbObjectForm {

    protected $interpro_id;
    protected $keyMvt = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->interpro_id = $options['interpro_id'];
        $this->keyMvt = $options['keyMvt'];
        
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->setWidget('identifiant', new WidgetSociete(array('interpro_id' => $this->interpro_id)));
        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setWidget("quantite", new sfWidgetFormInputFloat());
        $this->setWidget("prix_unitaire", new sfWidgetFormInputFloat());
        $this->setWidget("identifiant_analytique", new sfWidgetFormChoice(array('choices' => $this->getIdentifiantsAnalytiques())));

        $this->setValidator('identifiant', new ValidatorSociete(array('required' => false)));
        $this->setValidator("identifiant_analytique", new sfValidatorChoice(array('choices' => array_keys($this->getIdentifiantsAnalytiques()), 'required' => false)));
        $this->setValidator("libelle", new sfValidatorString(array('required' => false)));
        $this->setValidator("quantite", new sfValidatorNumber(array('required' => false)));
        $this->setValidator("prix_unitaire", new sfValidatorNumber(array('required' => false)));


        $this->configureTypeSociete(array(SocieteClient::SUB_TYPE_VITICULTEUR, SocieteClient::SUB_TYPE_NEGOCIANT));
        $this->widgetSchema->setNameFormat('facture_mouvement_etablissement_edition_ligne[%s]');
    }

    public function setDefaults($defaults) {
        parent::setDefaults($defaults);
        if($this->getObject()->getIdentifiant()) {
            var_dump($societe); exit;
            $societe = SocieteClient::getInstance()->findByIdentifiantSociete($this->getObject()->getIdentifiant());
            
            $this->setDefault('identifiant', "SOCIETE-" . $societe->identifiant . ',' . $societe->raison_sociale . ' ' . $societe->identifiant . ' / ' . $societe->siege->commune . ' ' . $societe->siege->code_postal . ' (Société)');
        }
        if (array_key_exists('quantite', $defaults) && $defaults['quantite']) {
            $this->setDefault('quantite', -1 * $defaults['quantite']);
        }
    }

    public function configureTypeSociete($types) {

        $this->getWidget('identifiant')->setOption('type_societe', $types);
        $this->getValidator('identifiant')->setOption('type_societe', $types);
    }

    public function getSociete() {
        return $this->getValidator('identifiant')->getDocument();
    }

    public function getIdentifiantsAnalytiques() {
        return ComptabiliteClient::getInstance()->findCompta()->getAllIdentifiantsAnalytiquesArrayForCompta();
    }

}
