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

        $this->setWidget('famille', new bsWidgetFormChoice(array('choices' => $this->getFamilles())));
        $this->setWidget('nom', new bsWidgetFormInput());
        if(count(self::getRegions()) > 1){
          $this->setWidget('region', new bsWidgetFormChoice(array('choices' => self::getRegions())));
        }
        $this->setWidget('nature_inao', new bsWidgetFormChoice(array('choices' => self::getNaturesInao())));
        $this->setWidget('no_accises', new bsWidgetFormInput());
        $this->setWidget('num_interne', new bsWidgetFormInput());
        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));
        $this->setWidget('site_fiche', new bsWidgetFormInput());

        $this->widgetSchema->setLabel('famille', 'Famille *');
        $this->widgetSchema->setLabel('nom', "Nom de l'établissement *");
        $this->widgetSchema->setLabel('nature_inao', 'Nature INAO');
        if(count(self::getRegions()) > 1){
          $this->widgetSchema->setLabel('region', 'Région viticole');
        }
        $this->widgetSchema->setLabel('no_accises', "N° d'Accise");
        $this->widgetSchema->setLabel('num_interne', "N° interne");
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');
        $this->widgetSchema->setLabel('site_fiche', 'Site Fiche Publique');

        $this->setValidator('famille', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getFamilles()))));
        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('nature_inao', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(self::getNaturesInao()))));
        if(count(self::getRegions()) > 1){
          $this->setValidator('region', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(self::getRegions()))));
        }
        $this->setValidator('site_fiche', new sfValidatorString(array('required' => false)));
        $this->setValidator('no_accises', new sfValidatorString(array('required' => false)));
        $this->setValidator('num_interne', new sfValidatorString(array('required' => false)));
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));

        if (!$this->etablissement->isCourtier()) {
            $this->setWidget('cvi', new bsWidgetFormInput());
            $this->widgetSchema->setLabel('cvi', 'CVI');
            $cviMsg = 'Le CVI doit impérativement être constitué de 10 chiffres';
            $this->setValidator('cvi', new sfValidatorString(array('required' => false, 'min_length' => 10, 'max_length' => 10),array('min_length' => $cviMsg, 'max_length' => $cviMsg)));

            $this->setWidget('ppm', new bsWidgetFormInput());
            $this->widgetSchema->setLabel('ppm', 'PPM');
            $ppmMsg = 'Le PPM doit impérativement commencer par une lettre suivie de 8 chiffres';
            $this->setValidator('ppm', new sfValidatorRegex(array('required' => false,
                                                                 'pattern' => "/^[A-Z]{1}[0-9]{8}$/",
                                                                 'min_length' => 9,
                                                                 'max_length' => 9),
                                                           array('invalid' => $ppmMsg,
                                                                 'min_length' => $ppmMsg,
                                                                 'max_length' => $ppmMsg,
                                                                 )
                                                            )
                                                          );

         } else {
            $this->setWidget('carte_pro', new bsWidgetFormInput());
            $this->widgetSchema->setLabel('carte_pro', 'N° Carte professionnelle');
            $this->setValidator('carte_pro', new sfValidatorString(array('required' => false)));
        }

        $this->widgetSchema->setNameFormat('etablissement_modification[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if (!$this->getObject()->nom) {
        	$this->setDefault('nom', $this->getObject()->getSociete()->getRaisonSociale());
        }
    }

    public function getFamilles()
    {
        return EtablissementFamilles::getFamilles();
    }

    public static function getRegions() {
        if(count(EtablissementClient::getRegions()) <= 1){
          return EtablissementClient::getRegions();
        }

        return array_merge(array("" => ""),EtablissementClient::getRegions());
    }

    public static function getNaturesInao() {
        return EtablissementClient::getNaturesInao();
    }

    public function getTypeDR() {
        return EtablissementClient::getTypeDR();
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);

        if (!$this->etablissement->isCourtier()) {
            $this->etablissement->setCvi($values['cvi']);
        } else {
            $this->etablissement->setCartePro($values['carte_pro']);
        }
        if((count(self::getRegions()) == 1)){
          $regions = array_keys(self::getRegions());
          $this->etablissement->region = $regions[0];
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
