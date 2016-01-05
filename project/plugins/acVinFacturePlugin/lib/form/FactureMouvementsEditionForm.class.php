<?php

/**
 * Description of FactureMouvementsEditionForm
 *
 * @author mathurin
 */
class FactureMouvementsEditionForm extends acCouchdbObjectForm {

    protected $interpro_id;

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->interpro_id = $options['interpro_id'];
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->setWidget("libelle", new sfWidgetFormInput());
        $this->setWidget('date', new bsWidgetFormInputDate());

        $this->setValidator("libelle", new sfValidatorString(array("required" => true)));
        $this->setValidator('date', new sfValidatorString(array('required' => false)));

        $this->embedForm('mouvements', new FactureMouvementEditionLignesForm($this->getObject()->mouvements, array('interpro_id' => $this->interpro_id)));

        $this->widgetSchema->setNameFormat('facture_mouvements_edition[%s]');
    }

    public function getFormTemplate() {
        $mouvementsFacture = new MouvementsFacture();
        $form_embed = new FactureMouvementEditionLigneForm($mouvementsFacture->getOrAdd('mouvements')->add('nouveau'), array('uniqkeyMvt' => uniqid(), 'interpro_id' => $this->interpro_id));
        $form = new FactureMouvementsCollectionTemplateForm($this, 'mouvements', $form_embed);
        return $form->getFormTemplate();
    }
    
     public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if ($form instanceof FactureMouvementEditionLignesForm) {
                if (isset($taintedValues[$key])) {
                    $form->bind($taintedValues[$key], $taintedFiles[$key]);
                    $this->updateEmbedForm($key, $form);
                }
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->getEmbeddedForms() as $key => $mouvementForm) {
            $mouvementForm->updateObject($values[$key]);
        }
    }

//    protected function doUpdateObject($values) {
//        parent::doUpdateObject($values);
//        $dateFacture = Date::getIsoDateFromFrenchDate($values["date"]);
//        $this->getObject()->set('date', $dateFacture);
//        $this->getObject()->remove('mouvements');
//
//        $mouvements = $this->getObject()->add('mouvements');
//
//        foreach ($this->embeddedForms as $embeddedKey => $embeddedForm) {
//            foreach ($embeddedForm->embeddedForms as $socKey => $etbForm) {
//                foreach ($etbForm->embeddedForms as $mvtKey => $mvtForm) {
//                    $mvtValues = $values["mouvements"];
//                    $societeKey = str_replace('SOCIETE-', '', $mvtValues[$socKey][$mvtKey]['identifiant']);
//                    $mvt = $mouvements->getOrAdd($societeKey)->getOrAdd($mvtKey);
//                    $mvt->identifiant_analytique = $mvtValues[$socKey][$mvtKey]['identifiant_analytique'];
//                    $mvt->identifiant_analytique_libelle = $mvtValues[$socKey][$mvtKey]['identifiant_analytique'];
//                    $mvt->identifiant_analytique_libelle_compta = $mvtValues[$socKey][$mvtKey]['identifiant_analytique'];
//                    $mvt->identifiant = $mvtValues[$socKey][$mvtKey]['identifiant'];
//                    $mvt->libelle = $mvtValues[$socKey][$mvtKey]['libelle'];
//                    $mvt->quantite = $mvtValues[$socKey][$mvtKey]['quantite'];
//                    $mvt->prix_unitaire = $mvtValues[$socKey][$mvtKey]['prix_unitaire'];
//                    $mvt->facture = 0;
//                    $mvt->facturable = 0;
//                    $mvt->region = "HORS_REGION";
//                    $mvt->date = date('Y-m-d');
//                }
//            }
//        }
//    }

}
