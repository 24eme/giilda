<?php

class EditionRevendicationForm extends sfForm {

    protected $_choices_produits;
    protected $revendication;
    protected $identifiant;
    protected $row;
    protected $produit_hash;
    protected $volume;
    protected $num_ligne;
    protected $code_douane;

    public function __construct(stdClass $revendication, $identifiant, $row, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($defaults, $options, $CSRFSecret);
        $this->revendication = $revendication;

        if (isset($identifiant) && isset($row)) {
            $this->initFields($identifiant, $row);
            $this->setDefaults($defaults);
        }
        
    }


    public function configure() {
        parent::configure();
        $this->setWidget('produit_hash', new sfWidgetFormChoice(array('choices' => $this->getProduits()), array('class' => 'autocomplete')));
        $this->setWidget('volume', new sfWidgetFormInput());
        $this->setValidator('produit_hash', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits()))));
        $this->setValidator('volume', new sfValidatorNumber(array('required' => true)));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('revendication_edition_row[%s]');
    }

    public function getVolumeProduitObj($revendication, $identifiant, $row) {
        $result = new stdClass();
        if (!isset($revendication->datas->$identifiant))
            throw new sfException("Le noeud d'identifiant $identifiant n'existe pas dans la revendication");

        $produitNode = RevendicationClient::getInstance()->getProduitNode($revendication, $identifiant, $row);
        if (!$produitNode)
            throw new sfException("Le noeud produit d'identifiant $identifiant et de ligne $row n'existe pas dans la revendication");

        $result->produit = $produitNode;
        $result->volume = $result->produit->volumes->$row;
        return $result;
    }

    public function getProduits() {
        if (is_null($this->_choices_produits)) {
            $this->_choices_produits = array_merge(array("" => ""), $this->getConfig()->formatProduits());
        }
        return $this->_choices_produits;
    }

    protected function getConfig() {

        return ConfigurationClient::getCurrent();
    }

    public function doUpdate() {
        $newProduitDouane = $this->getConfig()->get($this->values['produit_hash'])->getCodeDouane();
        $oldNode = RevendicationClient::getInstance()->getProduitNode($this->revendication, $this->identifiant, $this->row);
        if ($this->code_douane != $newProduitDouane) {
            $oldNode->statut = RevendicationProduits::STATUT_SUPPRIME;
            $this->createAndGetProduitNewNode($this->revendication, $this->identifiant, $newProduitDouane, $this->values['produit_hash'], $this->row, $oldNode);
        }
        $this->updateVolume($this->revendication, $this->identifiant, $newProduitDouane, $this->row, $this->num_ligne, $this->values['volume']);
        return $this->revendication;
    }

    public function getDefaults() {
        return $this->defaults;
    }

    public function createAndGetProduitNewNode($revendication, $identifiant, $codeDouane, $hash, $row, $oldNode) {
        if (!isset($revendication->datas->$identifiant))
            throw new sfException("Le noeud d'identifiant $identifiant n'existe pas dans la revendication");
        $produits = $revendication->datas->$identifiant->produits;
        if (!isset($produits->$codeDouane)) {
            $produits->$codeDouane = new stdClass();
            $libelle = $this->getConfig()->get($this->values['produit_hash'])->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce% %la%");
            $produits->$codeDouane->libelle_produit_csv = $libelle;
            $produits->$codeDouane->produit_hash = $hash;
            $produits->$codeDouane->produit_libelle = $libelle;
            $produits->$codeDouane->date_certification = date('Y-m-d');
        }
        $produits->$codeDouane->statut = RevendicationProduits::STATUT_MODIFIE;
        if (!isset($produits->$codeDouane->volumes))
            $produits->$codeDouane->volumes = new stdClass();
        $produits->$codeDouane->volumes->$row = $oldNode->volumes->$row;
        return $produits->$codeDouane;
    }

    public function updateVolume($revendication, $identifiant, $codeDouane, $row, $num_ligne, $volume) {
        if (!isset($revendication->datas->$identifiant))
            throw new sfException("Le noeud d'identifiant $identifiant n'existe pas dans la revendication");
        $produits = $revendication->datas->$identifiant->produits;
        if (!isset($produits->$codeDouane))
            throw new sfException("Le noeud produit d'identifiant $identifiant et de code douane $codeDouane n'existe pas dans la revendication");
        $produits->$codeDouane->volumes->$row->volume = $volume;
    }

    public function initFields($identifiant, $row) {
        $this->identifiant = $identifiant;
        $this->row = $row;
        $volumeProduitObj = $this->getVolumeProduitObj($this->revendication, $this->identifiant, $this->row);
        $this->produit_hash = $volumeProduitObj->produit->produit_hash;
        $this->code_douane = $this->getConfig()->get($this->produit_hash)->getCodeDouane();
        $this->volume = sprintf("%01.02f", round($volumeProduitObj->volume->volume, 2));
        $this->num_ligne = $volumeProduitObj->volume->num_ligne;
    }
    
    public function setDefaults($defaults) {
        parent::setDefaults($defaults);
        $this->defaults['produit_hash'] = $this->produit_hash;
        $this->defaults['volume'] = $this->volume;
        }
    
}