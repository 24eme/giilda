<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracMarcheForm extends acCouchdbObjectForm {

    protected $_choices_produits;
    protected $actual_campagne;
    protected $next_campagne;
    protected $isTeledeclarationMode;
    protected $defaultDomaine;
    
    const NONMILLESIMELABEL = "Non millésimé";

    public function __construct(Vrac $vrac, $isTeledeclarationMode = false, $defaultDomaine = null, $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $isTeledeclarationMode;
        $this->defaultDomaine = $defaultDomaine;
        parent::__construct($vrac, $options, $CSRFSecret);
    }

    public function configure() {
        $this->actual_campagne = ConfigurationClient::getInstance()->getCurrentCampagne();
        $this->next_campagne = (date('Y') > substr($this->actual_campagne, 0, 4) && date('m') > 3) ?
                ConfigurationClient::getInstance()->getNextCampagne($this->actual_campagne) : $this->actual_campagne;

        $originalArray = array('0' => 'Non', '1' => 'Oui');

        if (!$this->isTeledeclarationMode) {

            $this->setWidget('label', new bsWidgetFormChoice(array('choices' => $this->getLabels(), 'multiple' => true, 'expanded' => true)));
            $this->setValidator('label', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($this->getLabels()))));
            $this->getWidget('label')->setLabel("Label");
        }

        $this->getDomaines();
        $this->getMillesimes();
        $this->setWidget('produit', new bsWidgetFormChoice(array('choices' => $this->getProduits()), array('class' => 'autocomplete')));
        $this->setWidget('millesime', new bsWidgetFormChoice(array('choices' => $this->millesimes), array('class' => 'autocomplete permissif')));
        $this->setWidget('categorie_vin', new bsWidgetFormChoice(array('choices' => $this->getCategoriesVin(), 'expanded' => true)));
        $this->setWidget('domaine', new bsWidgetFormChoice(array('choices' => $this->domaines), array('class' => 'autocomplete permissif')));
        $this->setWidget('raisin_quantite', new bsWidgetFormInput());
        $this->setWidget('jus_quantite', new bsWidgetFormInput());
        $contenance = array();
        foreach (array_keys(VracClient::getInstance()->getContenances()) as $c) {
            $contenance[$c] = $c;
        }
        $this->setWidget('bouteilles_contenance_libelle', new sfWidgetFormChoice(array('choices' => $contenance)));
        $this->setWidget('prix_initial_unitaire', new bsWidgetFormInput());

        $this->widgetSchema->setLabels(array(
            'produit' => 'produit',
            'millesime' => $this->getMillesimeLabel(),
            'categorie_vin' => 'Type',
            'domaine' => 'Nom du domaine',
            'raisin_quantite' => 'Quantité',
            'jus_quantite' => 'Volume proposé',
            'bouteilles_contenance_libelle' => 'Contenance',
            'prix_initial_unitaire' => 'Prix'
        ));
        $validatorForNumbers = new sfValidatorRegex(array('required' => false, 'pattern' => "/^[0-9]*.?,?[0-9]+$/"));
        
        $this->setValidator('produit', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits()))));

        $this->setValidator('millesime', new sfValidatorInteger(array('required' => true)));

        $this->setValidator('categorie_vin', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCategoriesVin()))));
        $this->setValidator('domaine', new sfValidatorString(array('required' => false)));

        $this->setValidator('raisin_quantite', new sfValidatorNumber(array('required' => true)));
        $this->setValidator('jus_quantite', new sfValidatorNumber(array('required' => true)));

        $this->setValidator('bouteilles_contenance_libelle', new sfValidatorString(array('required' => true)));
        $this->setValidator('prix_initial_unitaire', new sfValidatorNumber(array('required' => true)));

        
        $this->validatorSchema['jus_quantite']->setMessage('invalid', 'La quantité "%value%" n\'est pas un nombre.');
        $this->validatorSchema['raisin_quantite']->setMessage('invalid', 'La quantité "%value%" n\'est pas un nombre.');

        $this->validatorSchema['prix_initial_unitaire']->setMessage('invalid', 'Le prix "%value%" n\'est pas un nombre.');


        $this->validatorSchema['produit']->setMessage('required', 'Le choix d\'un produit est obligatoire');
        $this->validatorSchema['prix_initial_unitaire']->setMessage('required', 'Le prix doit être renseigné');

        $this->validatorSchema['millesime']->setMessage('required', 'Le millésime doit être renseigné');

        if ($this->getObject()->hasPrixVariable()) {
            $this->getWidget('prix_initial_unitaire')->setLabel('Prix initial');
            $this->setWidget('prix_unitaire', new bsWidgetFormInput(array('label' => 'Prix définitif')));
            $this->setValidator('prix_unitaire', new sfValidatorNumber(array('required' => false)));
        }
        if ($this->getObject()->type_transaction == VracClient::TYPE_TRANSACTION_RAISINS) {
        	unset($this['jus_quantite']);
        } else {
        	unset($this['raisin_quantite']);
        }
        if ($this->getObject()->type_transaction != VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE) {
        	unset($this['bouteilles_contenance_libelle']);
        }
        

        $this->setWidget('enlevement_date', new bsWidgetFormInput(array('label' => 'Date limite')));
        $this->getWidget('enlevement_date')->setLabel("Date limite");
        $this->setValidator('enlevement_date', new sfValidatorString(array('required' => false)));


        $this->widgetSchema->setNameFormat('vrac[%s]');
        $this->validatorSchema->setPostValidator(new ValidatorVracMarche());
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($this->getObject()->hasPrixVariable()) {
            $this->setDefault('prix_unitaire', $this->getObject()->_get('prix_unitaire'));
        }
        if (!$this->getObject()->bouteilles_contenance_libelle) {
            $this->setDefault('bouteilles_contenance_libelle', 'Bouteille 75 cl');
        }
        if (!$this->getObject()->millesime) {
            if (!$this->getObject()->type_transaction) {
                $this->setDefault('millesime', "0");
            } else {
                if (($this->getObject()->type_transaction == VracClient::TYPE_TRANSACTION_MOUTS) || ($this->getObject()->type_transaction == VracClient::TYPE_TRANSACTION_RAISINS)) {
                    $this->setDefault('millesime', substr($this->next_campagne, 0, 4));
                } else {
                    $this->setDefault('millesime', "0");
                }
            }
        } else {
            $this->setDefault('millesime', $this->getObject()->millesime);
        }

        if ($this->defaultDomaine) {
            $this->setDefault('domaine', $this->defaultDomaine);
        }
    }

    public function getProduits() {
        if (is_null($this->_choices_produits)) {
            $this->_choices_produits = array_merge(array("" => ""), $this->getObject()->getProduitsConfig());
        }
        return $this->_choices_produits;
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->update();
        $this->getObject()->domaine = strtoupper(KeyInflector::unaccent($this->values['domaine']));
        if ($values['millesime'] === 0) {
            $this->getObject()->millesime = null;
        }
    }

    public function getDomaines() {
        $domaines = VracDomainesView::getInstance()->findDomainesByVendeur($this->getObject()->vendeur_identifiant);
        $this->domaines = array('' => '');
        foreach ($domaines->rows as $resultDomaine) {
            $d = $resultDomaine->key[VracDomainesView::KEY_DOMAINE];
            $this->domaines[$d] = $d;
        }
        if($this->defaultDomaine){
            $this->domaines[$this->defaultDomaine] = $this->defaultDomaine;
        }
    }

    public function getMillesimes() {
        $this->millesimes = array('0' => self::NONMILLESIMELABEL);

        $campagnesView = array($this->next_campagne => $this->next_campagne);
        $campagnesView[$this->actual_campagne] = $this->actual_campagne;

        $campagnesView = array_merge($campagnesView, VracClient::getInstance()->listCampagneByEtablissementId($this->getObject()->vendeur_identifiant));
        if (!$this->getObject()->millesime && $this->getObject()->millesime != 0) {
            $campagnesView = array_merge($campagnesView, array('' . $this->getObject()->millesime => '' . $this->getObject()->millesime));
        }
        foreach ($campagnesView as $campagne) {
            $millesime = substr($campagne, 0, 4);
            $this->millesimes[$millesime] = '' . $millesime;
        }
    }

    public function getCategoriesVin() {

        return VracClient::$categories_vin;
    }

    protected function getConfig() {

        return ConfigurationClient::getCurrent();
    }

    protected function getLabels() {

        return $this->getConfig()->labels->toArray();
    }

    private function getCurrentYear() {
        $year = date('Y');
        return (int) $year + 1;
    }

    private function getMillesimeLabel() {
        return $this->getObject()->getMillesimeLabel();
    }

    public function getNextMillesime() {
        return substr($this->next_campagne, 0, 4);
    }

    public function getActuelMillesime() {
        return substr($this->actual_campagne, 0, 4);
    }

}
