<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteModificationForm
 * @author mathurin
 */
class EtablissementModificationForm extends CompteGeneriqueForm {

    private $etablissement;
    private $liaisons_operateurs = null;

    public function __construct(Etablissement $etablissement, $options = array(), $CSRFSecret = null) {
        $this->etablissement = $etablissement;
        parent::__construct($this->etablissement, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->setWidget('nom', new bsWidgetFormInput());
        $this->setWidget('statut', new bsWidgetFormChoice(array('choices' => self::getStatuts(), 'multiple' => false, 'expanded' => true)));
        $this->setWidget('region', new bsWidgetFormChoice(array('choices' => self::getRegions())));
        $this->setWidget('no_accises', new bsWidgetFormInput());
        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));        $this->setWidget('site_fiche', new bsWidgetFormInput());


        $this->widgetSchema->setLabel('nom', 'Nom du chai *');
        $this->widgetSchema->setLabel('statut', 'Statut *');
        $this->widgetSchema->setLabel('region', 'Région viticole *');
        $this->widgetSchema->setLabel('no_accises', "N° d'Accise");
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');
        $this->widgetSchema->setLabel('site_fiche', 'Site Fiche Publique');


        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('statut', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(self::getStatuts()))));
        $this->setValidator('region', new sfValidatorChoice(array('required' => true, 'choices' => array_keys(self::getRegions()))));
        $this->setValidator('site_fiche', new sfValidatorString(array('required' => false)));
        $this->setValidator('no_accises', new sfValidatorString(array('required' => false)));
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));


        if (!$this->etablissement->isCourtier()) {
            $this->setWidget('cvi', new bsWidgetFormInput());
            $this->widgetSchema->setLabel('cvi', 'CVI');
            $this->setValidator('cvi', new sfValidatorString(array('required' => false)));
        } else {
            $this->setWidget('carte_pro', new bsWidgetFormInput());
            $this->widgetSchema->setLabel('carte_pro', 'N° Carte professionnelle');
            $this->setValidator('carte_pro', new sfValidatorString(array('required' => false)));
        }

        if ($this->etablissement->isNew()) {
            $this->widgetSchema['statut']->setAttribute('disabled', 'disabled');
        }     
     
        $this->widgetSchema->setNameFormat('etablissement_modification[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        
        $this->setDefault('adresse', $this->etablissement->siege->adresse);
        $this->setDefault('code_postal', $this->etablissement->siege->code_postal);
        $this->setDefault('commune', $this->etablissement->siege->commune);
        $this->setDefault('pays', $this->etablissement->siege->pays);
        $this->setDefault('adresse_complementaire', $this->etablissement->adresse_complementaire);
        $this->setDefault('cedex', $this->etablissement->cedex);
    }

   
    public static function getRegions() {
        return EtablissementClient::getRegions();
    }

    public function getTypeDR() {
        return EtablissementClient::getTypeDR();
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);

        $this->etablissement->setAdresse($values['adresse']);
        $this->etablissement->setCommune($values['commune']);
        $this->etablissement->setPays($values['pays']);
        $this->etablissement->setCedex($values['cedex']);
        $this->etablissement->setAdresseComplementaire($values['adresse_complementaire']);
        $this->etablissement->setCodePostal($values['code_postal']);
        
        if (!$this->etablissement->isCourtier()) {
            $this->etablissement->setCvi($values['cvi']);
        } else {
            $this->etablissement->setCartePro($values['carte_pro']);
        }
        
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    public function getFormTemplate() {
        $etablissement = new Etablissement();
        $form_embed = new LiaisonItemForm($etablissement->liaisons_operateurs->add());
        $form = new EtablissementCollectionTemplateForm($this, 'liaisons_operateurs', $form_embed);
        return $form->getFormTemplate();
    }

    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->liaisons_operateurs->remove($key);
    }

}

?>
