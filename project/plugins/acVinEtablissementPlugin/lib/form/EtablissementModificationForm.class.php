<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteModificationForm
 * @author mathurin
 */
class EtablissementModificationForm extends acCouchdbObjectForm {

    private $etablissement;
    private $liaisons_operateurs = null;

    public function __construct(Etablissement $etablissement, $options = array(), $CSRFSecret = null) {
        $this->etablissement = $etablissement;
        parent::__construct($this->etablissement, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->setWidget('nom', new bsWidgetFormInput());
        $this->setWidget('statut', new bsWidgetFormChoice(array('choices' => $this->getStatuts(), 'multiple' => false, 'expanded' => true)));
        $this->setWidget('region', new bsWidgetFormChoice(array('choices' => $this->getRegions())));
        $this->setWidget('no_accises', new bsWidgetFormInput());
        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));
        $this->setWidget('site_fiche', new bsWidgetFormInput());


        $this->widgetSchema->setLabel('nom', 'Nom du chai *');
        $this->widgetSchema->setLabel('statut', 'Statut *');
        $this->widgetSchema->setLabel('region', 'Région viticole *');
        $this->widgetSchema->setLabel('no_accises', "N° d'Accise");
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');
        $this->widgetSchema->setLabel('site_fiche', 'Site Fiche Publique');



        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('statut', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));
        $this->setValidator('region', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getRegions()))));
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

                $this->setWidget('statut', new bsWidgetFormChoice(array('choices' => $this->getStatuts(), 'multiple' => false, 'expanded' => true)));
        $this->setWidget('civilite', new bsWidgetFormChoice(array('choices' => CompteForm::getCiviliteList())));
        $this->setWidget('nom', new bsWidgetFormInput());
        $this->setWidget('prenom', new bsWidgetFormInput());
        $this->setWidget('fonction', new bsWidgetFormInput());
        $this->setWidget('commentaire', new bsWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->setWidget('adresse', new bsWidgetFormInput());
        $this->setWidget('adresse_complementaire', new bsWidgetFormInput());
        $this->setWidget('code_postal', new bsWidgetFormInput());
        $this->setWidget('commune', new bsWidgetFormInput());
        $this->setWidget('cedex', new bsWidgetFormInput());
        $this->setWidget('pays', new bsWidgetFormChoice(array('choices' => CompteForm::getCountryList()), array("class" => "select2 form-control")));
        $this->setWidget('droits', new bsWidgetFormChoice(array('choices' => CompteForm::getDroits(), 'multiple' => true, 'expanded' => true)));

        $this->setWidget('email', new bsWidgetFormInput());
        $this->setWidget('telephone_perso', new bsWidgetFormInput());
        $this->setWidget('telephone_bureau', new bsWidgetFormInput());
        $this->setWidget('telephone_mobile', new bsWidgetFormInput());
        $this->setWidget('fax', new bsWidgetFormInput());
        $this->setWidget('site_internet', new bsWidgetFormInput());

        $this->widgetSchema->setLabel('statut', 'Statut *');
        $this->widgetSchema->setLabel('civilite', 'Civilite *');
        $this->widgetSchema->setLabel('nom', 'Nom *');
        $this->widgetSchema->setLabel('prenom', 'Prenom');
        $this->widgetSchema->setLabel('fonction', 'Fonction *');
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');

        $this->widgetSchema->setLabel('adresse', 'N° et nom de rue *');
        $this->widgetSchema->setLabel('adresse_complementaire', 'Adresse complémentaire');
        $this->widgetSchema->setLabel('code_postal', 'CP *');
        $this->widgetSchema->setLabel('commune', 'Ville *');
        $this->widgetSchema->setLabel('cedex', 'Cedex');
        $this->widgetSchema->setLabel('pays', 'Pays *');
        $this->widgetSchema->setLabel('droits', 'Droits *');

        $this->widgetSchema->setLabel('email', 'E-mail');
        $this->widgetSchema->setLabel('telephone_perso', 'Telephone Perso.');
        $this->widgetSchema->setLabel('telephone_bureau', 'Telephone Bureau');
        $this->widgetSchema->setLabel('telephone_mobile', 'Mobile');
        $this->widgetSchema->setLabel('fax', 'Fax');
        $this->widgetSchema->setLabel('site_internet', 'Site Internet');

        $this->setValidator('statut', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));
        $this->setValidator('civilite', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(CompteForm::getCiviliteList()))));
        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('prenom', new sfValidatorString(array('required' => false)));
        $this->setValidator('fonction', new sfValidatorString(array('required' => false)));
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));
        $this->setValidator('adresse', new sfValidatorString(array('required' => false)));
        $this->setValidator('adresse_complementaire', new sfValidatorString(array('required' => false)));
        $this->setValidator('code_postal', new sfValidatorString(array('required' => false)));
        $this->setValidator('commune', new sfValidatorString(array('required' => false)));
        $this->setValidator('cedex', new sfValidatorString(array('required' => false)));
        $this->setValidator('pays', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(CompteForm::getCountryList()))));
        $this->setValidator('droits', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys(CompteForm::getDroits()))));
        $this->setValidator('email', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_perso', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_bureau', new sfValidatorString(array('required' => false)));
        $this->setValidator('telephone_mobile', new sfValidatorString(array('required' => false)));
        $this->setValidator('fax', new sfValidatorString(array('required' => false)));
        $this->setValidator('site_internet', new sfValidatorString(array('required' => false)));

        
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

    public function getStatuts() {

        return EtablissementClient::getStatuts();
    }

    public function getAdresseSociete() {

        return array(1 => 'Oui', 0 => 'Non');
    }

    public function getRegions() {
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

//        $this->updateObject();  
//        
//        $old_compte = $this->etablissement->compte; 
//        $switch = false;
//         if($this->values['adresse_societe'] && !is_null($this->values['statut']) && !$this->etablissement->getSociete()->isManyEtbPrincipalActif()
//            && ($this->values['statut'] != ($socStatut = $this->etablissement->getSociete()->statut))){
//                throw new sfException("Il s'agit de l'établissement pricipal de la société, il ne peut être suspendu. Pour le suspendre, vous devez suspendre la société.");
//        }
//   }
//    public function bind(array $taintedValues = null, array $taintedFiles = null) {
//        foreach ($this->embeddedForms as $key => $form) {
//            if ($form instanceof LiaisonsItemForm) {
//                if (isset($taintedValues[$key])) {
//                    $form->bind($taintedValues[$key], $taintedFiles[$key]);
//                    $this->updateEmbedForm($key, $form);
//                }
//            }
//        }
//
//        if (!array_key_exists('statut', $taintedValues)) {
//            $taintedValues['statut'] = $this->getObject()->statut;
//        }
//
//        parent::bind($taintedValues, $taintedFiles);
//    }

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

    /*
      protected function getRecettesLocales() {
      $douanes = SocieteAllView::getInstance()->findByInterproAndStatut('INTERPRO-declaration', SocieteClient::STATUT_ACTIF, array(SocieteClient::SUB_TYPE_DOUANE));

      $douanesList = array();
      foreach ($douanes as $key => $douane) {
      $douaneObj = SocieteClient::getInstance()->find($douane->id);
      $douanesList[$douane->id] = $douane->key[SocieteAllView::KEY_RAISON_SOCIALE].' '.$douaneObj->siege->commune.' ('.$douaneObj->siege->code_postal.')';
      }
      return $douanesList;
      }
     */
}

?>
