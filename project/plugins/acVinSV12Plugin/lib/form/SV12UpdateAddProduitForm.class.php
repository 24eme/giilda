<?php

class SV12UpdateAddProduitForm extends acCouchdbForm
{
  protected $_sv12 = null;
  protected $_config = null;
  protected $_choices_produits;
  protected $_raisinetmout = true;

  public function __construct(SV12 $sv12, $options = array(), $CSRFSecret = null)
  {
    $this->_sv12 = $sv12;
    $this->_raisinetmout = (isset($options['raisinetmout']))? $options['raisinetmout'] : true;
    $this->_config = $this->getConfig();
    $defaults = (SV12Configuration::getInstance()->noViti())? array() : array('withviti' => 'withviti');
    parent::__construct($sv12, $defaults, $options, $CSRFSecret);
  }

    public function configure()
    {
      $this->setWidget('hashref', new bsWidgetFormChoice(array('choices' => $this->getChoices()), array("class" => "form-control select2")));
      if($this->_raisinetmout){
        $this->setWidget('raisinetmout', new bsWidgetFormChoice(array('choices' => array(VracClient::TYPE_TRANSACTION_RAISINS => 'Raisins', VracClient::TYPE_TRANSACTION_MOUTS => 'Moûts'), 'expanded'=>true)));
      }
      $this->setWidget('identifiant', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-declaration', 'familles' => array(EtablissementFamilles::FAMILLE_PRODUCTEUR))));
      $this->setWidget('withviti', new  bsWidgetFormInputCheckbox(array('value_attribute_value' => 'withviti')));
      $this->setWidget('volume', new bsWidgetFormInputFloat(array()));

      $this->widgetSchema->setLabel('hashref', 'Produit&nbsp;: ');
      if($this->_raisinetmout){
        $this->widgetSchema->setLabel('raisinetmout', 'Raisins et moûts&nbsp;:');
      }
			$this->widgetSchema->setLabel('identifiant', 'Viticulteur&nbsp;:');
			$this->widgetSchema->setLabel('withviti', "Affecter l'enlevement à un viti");
      $this->widgetSchema->setLabel('volume', "Volume&nbsp;:");

      $this->setValidator('hashref', new sfValidatorChoice(array('required' => true,  'choices' => array_keys($this->getProduits())),array('required' => "Aucun produit n'a été saisi !")));
      if($this->_raisinetmout){
			     $this->setValidator('raisinetmout', new sfValidatorChoice(array('choices' => array(VracClient::TYPE_TRANSACTION_RAISINS, VracClient::TYPE_TRANSACTION_MOUTS), 'required' => false)));
      }
			$this->setValidator('identifiant', new ValidatorEtablissement(array('required' => false)));
			$this->setValidator('withviti', new sfValidatorChoice(array('required' => false, 'choices' => array('withviti'))));
      $this->setValidator('volume', new sfValidatorNumber(array('required' => false, 'min' => 0)));

      if ($labels = $this->getLabels()) {
          $this->setWidget('label', new sfWidgetFormChoice(array('choices' => $labels, 'expanded' => true, 'multiple' => true), array("class" => "")));
          $this->widgetSchema->setLabel('label', 'Label&nbsp;: ');
          $this->setValidator('label', new sfValidatorChoice(array('required' => true, 'multiple' => true,  'choices' => array_keys($labels)),array('required' => "Aucun label n'a été saisi !")));
      }

      $this->validatorSchema->setPostValidator(new SV12AddProduitValidator());
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
        $date = $this->_sv12->getFirstDayOfPeriode();
        return $this->_config->formatProduitsSV12($date);
    }

    public function getLabels() {
        return (method_exists($this->_config, 'getVracLabels'))? $this->_config->getVracLabels() : null;
    }

    public function addProduit()
    {
        $label = (isset($this->values['label']) && $this->values['label'])? $this->values['label'] : null;
      if (!$this->isValid()) {
	       throw $this->getErrorSchema();
      }

      $typeKey = ($this->_raisinetmout)? strtoupper($this->values['raisinetmout']) : SV12Client::SV12_TYPEKEY_VENDANGE ;

      if (!isset($this->values['withviti']) || !$this->values['withviti']) {
             $key = SV12Client::SV12_KEY_SANSVITI.'-'.$typeKey.str_replace('/', '-', $this->values['hashref']);
             if ($label && $label != ['conv']) {
                $key .=  '-'.implode('_', $label);
             }
	         $sv12Contrat = $this->_sv12->contrats->add($key);
             $sv12Contrat->updateNoContrat($this->getConfig()->getConfigurationProduit($this->values['hashref']), array('contrat_type' => $typeKey, 'volume' => $this->values['volume']));
             if ($label) {
                 $sv12Contrat->produit_libelle = $this->getProduitLibelleWithLabel($sv12Contrat->produit_libelle, $label);
                 $sv12Contrat->add('labels', $label);
             }
	         return $sv12Contrat;
      }

      $etablissement = EtablissementClient::getInstance()->find($this->values['identifiant']);
      $key = SV12Client::SV12_KEY_SANSCONTRAT.'-'.$etablissement->identifiant.'-'.$typeKey.str_replace('/', '-', $this->values['hashref']);
      if ($label && $label != ['conv']) {
         $key .=  '-'.implode('_', $label);
      }
      $sv12Contrat = $this->_sv12->contrats->add($key);

      $sv12Contrat->updateNoContrat($this->getConfig()->getConfigurationProduit($this->values['hashref']), array('vendeur_identifiant' => $etablissement->identifiant, 'vendeur_nom' => $etablissement->nom, 'contrat_type' => $typeKey,'volume' => $this->values['volume']));
      if ($label) {
          $sv12Contrat->produit_libelle = $this->getProduitLibelleWithLabel($sv12Contrat->produit_libelle, $label);
          $sv12Contrat->add('labels', $label);
      }

    }

    public function getProduitLibelleWithLabel($produit_libelle, $labels) {
      $labelsLibelles = $this->getLabels();
      if (isset($labelsLibelles['conv'])) {
          unset($labelsLibelles['conv']);
      }
      $libelles = [];
      foreach($labels as $label) {
          if (isset($labelsLibelles[$label])) $libelles[] = $labelsLibelles[$label];
      }
      return trim(trim($produit_libelle).' '.implode(', ', $libelles));
    }

    public function getConfig()
    {
        return ConfigurationClient::getConfiguration($this->_sv12->getDate());
    }
}
