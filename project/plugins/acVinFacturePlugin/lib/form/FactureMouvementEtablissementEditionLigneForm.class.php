<?php

class FactureMouvementEtablissementEditionLigneForm extends acCouchdbObjectForm {

    protected $interpro_id;
    protected $interproFacturable;
    protected $isreadonly = array();

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        if(isset($options['interpro_id'])) {
            $this->interpro_id = $options['interpro_id'];
        }
        if(isset($options['interproFacturable'])) {
            $this->interproFacturable = $options['interproFacturable'];
        }
        if ($object && $object->facture) {
            $this->isreadonly = array('readonly' => 'readonly', 'disabled' => 'disabled');
        }
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('identifiant', new WidgetSociete(array('interpro_id' => $this->interpro_id, 'type_societe' => array(SocieteClient::TYPE_OPERATEUR,SocieteClient::TYPE_AUTRE)), $this->isreadonly));
        $this->setWidget("identifiant_analytique", new sfWidgetFormChoice(array('choices' => $this->getIdentifiantsAnalytiques()), $this->isreadonly));
        $this->setWidget("libelle", new bsWidgetFormInput(array(), $this->isreadonly));
        $this->setWidget("quantite", new bsWidgetFormInputFloat(array(), $this->isreadonly));
        $this->setWidget("prix_unitaire", new bsWidgetFormInputFloat(array(), $this->isreadonly));

        $this->setValidator('identifiant', new ValidatorSociete(array('required' => false)));
        $this->setValidator("identifiant_analytique", new sfValidatorChoice(array('choices' => array_keys($this->getIdentifiantsAnalytiques()), 'required' => false)));
        $this->setValidator("libelle", new sfValidatorString(array('required' => false)));
        $this->setValidator("quantite", new sfValidatorNumber(array('required' => false)));
        $this->setValidator("prix_unitaire", new sfValidatorNumber(array('required' => false)));

        $this->configureTypeSociete(array(SocieteClient::TYPE_OPERATEUR));
        $this->widgetSchema->setNameFormat('facture_mouvement_etablissement_edition_ligne[%s]');
    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      $this->setDefault('identifiant', EtablissementClient::getInstance()->getSocieteIdentifiant($this->getObject()->identifiant));
      $lastMouvement = $this->getObject()->getDocument()->getLastMouvement($this->interproFacturable);
      if ($this->getObject()->getKey() == 'nouveau' && $lastMouvement) {
        $this->setDefault('identifiant_analytique', $lastMouvement->identifiant_analytique);
        $this->setDefault('libelle', $lastMouvement->libelle);
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
        return ComptabiliteClient::getInstance()->findCompta($this->interproFacturable)->getAllIdentifiantsAnalytiquesArrayForCompta();
    }

}
