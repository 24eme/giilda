<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AddRowRevendicationForm
 * @author mathurin
 */
class AddRowRevendicationForm extends EditionRevendicationForm {

    protected $revendication;

    public function __construct(stdClass $revendication, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->revendication = $revendication;
        parent::__construct($revendication, null, null, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
        $this->setWidget('etablissement', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-inter-loire', 'familles' => array(EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::FAMILLE_PRODUCTEUR))));
        $this->setValidator('etablissement', new ValidatorEtablissement(array('required' => true)));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('revendication_creation_row[%s]');
    }

    public function doUpdate() {
        $etb = EtablissementClient::getInstance()->find($this->values['etablissement']);
        return $this->createAndGetEtablissementNode($etb);
//        $this->initFields($etb->identifiant, 'SAISIE');
//        return parent::doUpdate();
    }

    public function createAndGetEtablissementNode(Etablissement $etb) {
        $identifiant = $etb->identifiant;
        if (!isset($this->revendication->datas->$identifiant)) {
            $etbNode = $this->revendication->datas->$identifiant = new stdClass();
            $etbNode->declarant_cvi = $etb->cvi;
            $etbNode->declarant_nom = $etb->nom;
            $etbNode->commune = $etb->siege->commune;
            $etbNode->produits = new stdClass();
        }
        $etbNode = $this->revendication->datas->$identifiant;
        
        $hash = $this->values['produit_hash'];
        $libelle = $this->getConfig()->get($hash)->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce% %la%");
        $newProduitDouane = $this->getConfig()->get($hash)->getCodeDouane();
        if (isset($etbNode->produits->$newProduitDouane) && ($etbNode->produits->$newProduitDouane->statut != RevendicationProduits::STATUT_SUPPRIME))
            throw new sfException("Le produit $libelle a déjà été ajouté pour le viticuleur $etb->nom");
        $etbProd = $etbNode->produits->$newProduitDouane = new stdClass();
        $etbProd->date_certification = date('Ymd');
        $etbProd->libelle_produit_csv = $libelle;
        $etbProd->produit_hash = $hash;
        $etbProd->produit_libelle = $libelle;
        $etbProd->statut = "saisie";
        if (!isset($etbProd->volumes)) {
            $etbProd->volumes = new stdClass();
        }
        $ligne = 'SAISIE';
        if (!isset($etbProd->volumes->$ligne)) {
            $etbProd->volumes->$ligne = new stdClass();
        }
        $etbProd->volumes->$ligne->num_ligne = 0;
        $etbProd->volumes->$ligne->volume = $this->values['volume'];
        $etbProd->volumes->$ligne->bailleur_identifiant = null;
        $etbProd->volumes->$ligne->bailleur_nom = null;
        $etbProd->volumes->$ligne->date_insertion = date('Y-m-d');
        $etbProd->volumes->$ligne->ligne = "";
        return $this->revendication;
    }

}

?>
