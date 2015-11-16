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

        $this->getObject()->mouvements->add("nouvelle");

        $this->embedForm('mouvements', new FactureMouvementEditionLignesForm($this->getObject()->mouvements, array('interpro_id' => $this->interpro_id)));

        $this->widgetSchema->setNameFormat('facture_mouvements_edition[%s]');
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

    public function getFormTemplate() {
        $mouvementsFacture = new MouvementsFacture();
        $form_embed = new FactureMouvementEditionLigneForm($mouvementsFacture->getOrAdd('mouvements')->add(), array('key' => uniqid(), 'interpro_id' => $this->interpro_id));
        $form = new FactureMouvementsCollectionTemplateForm($this, 'mouvements', $form_embed);
        return $form->getFormTemplate();
    }
    
    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $dateFacture = Date::getIsoDateFromFrenchDate($values["date"]);
        $this->getObject()->set('date',$dateFacture);
        if ($this->getObject()->mouvements->exist("nouvelle")) {
            $mvtsEtb = $this->getObject()->mouvements->get("nouvelle")->toArray(true, false);
            $nouveauMvt = $this->getObject()->mouvements->get("nouvelle")->get("nouvelle");
            $this->getObject()->mouvements->remove("nouvelle");
            $identifiant = preg_replace('/^SOCIETE-/', '', $nouveauMvt->identifiant);
            $this->getObject()->mouvements->getOrAdd($identifiant)->add(uniqid(), $nouveauMvt);
        }
        foreach ($this->getObject()->mouvements as $mouvementEtb) {
            foreach ($mouvementEtb as $mouvement) {
                $mouvement->facturable = 1;
                $mouvement->facture = 0;
                $mouvement->region = EtablissementClient::HORS_REGION;
                $mouvement->date = $dateFacture;
            }
        }

//      $this->getObject()->lignes->cleanLignes();
        $this->getObject()->valide->date_saisie = $values['date'];
    }

}
