<?php

class SV12UpdateAddProduitForm extends acCouchdbForm 
{
    protected $_sv12 = null;
    protected $_config = null;
    protected $_choices_produits;
    
	public function __construct(SV12 $sv12, $options = array(), $CSRFSecret = null) 
	{
		$this->_sv12 = $sv12;
        $this->_config = $this->getConfig();
        $defaults = array();
        parent::__construct($sv12, $defaults, $options, $CSRFSecret);
    }
    
    public function configure() 
    {
        $this->setWidgets(array(
            'hashref' => new sfWidgetFormChoice(array('choices' => $this->getChoices())),
        ));
        $this->widgetSchema->setLabels(array(
            'hashref' => 'Produit: ',
        ));

        $this->setValidators(array(
            'hashref' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits())),array('required' => "Aucun produit n'a été saisi !")),
        ));
        $this->widgetSchema->setNameFormat('sv12_add_produit[%s]');
    }

    public function getChoices() 
    {
        if (is_null($this->_choices_produits)) {
            $this->_choices_produits = array_merge(array("" => ""),
												   array("nouveau" => $this->getProduits()));
        }
        return $this->_choices_produits;
    }

    public function getProduits() 
    {
        return $this->_config->formatProduits();
    }

    public function addProduit() 
    {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }
        $sv12Contrat = $this->_sv12->contrats->add(str_replace('/', '-', $this->values['hashref']));
        $sv12Contrat->updateNoContrat($this->getConfig()->get($this->values['hashref']));
        return $sv12Contrat;
    }
    
    public function getConfig() 
    {
        return ConfigurationClient::getCurrent();
    }
}