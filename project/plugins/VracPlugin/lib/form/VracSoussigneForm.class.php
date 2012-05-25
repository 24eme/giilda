<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracSoussigneForm extends acCouchdbFormDocumentJson {

   private $vendeurs = array('Etablissements-XXXXX' => 
                                array('nom' => 'mathurin',
                                    'cvi' => '7558754230',
                                    'num_assise' => '7558754230',
                                    'num_tva_intracomm' => '7558754230',
                                    'adresse' => '226 rue de tolbiac' ,
                                    'commune' => 'paris',
                                    'code_postal' => '75013', 
                                    'departement' => '75'),
                            'Etablissements-XXXXY' => 
                                array('nom' => 'vincent',
                                    'cvi' => '7558454230',
                                    'num_assise' => '7558785230',
                                    'num_tva_intracomm' => '7533754230',
                                    'adresse' => 'ailleurs' ,
                                    'commune' => 'paris',
                                    'code_postal' => '75002', 
                                    'departement' => '75'));
   
   private $acheteurs = array('Etablissements-XXXXX' => 
                                array('nom' => 'mathurin',
                                    'cvi' => '7558754230',
                                    'num_assise' => '7558754230',
                                    'num_tva_intracomm' => '7558754230',
                                    'adresse' => '226 rue de tolbiac' ,
                                    'commune' => 'paris',
                                    'code_postal' => '75013', 
                                    'departement' => '75'),
                              'Etablissements-XXXXY' => 
                                array('nom' => 'vincent',
                                    'cvi' => '7558454230',
                                    'num_assise' => '7558785230',
                                    'num_tva_intracomm' => '7533754230',
                                    'adresse' => 'ailleurs' ,
                                    'commune' => 'paris',
                                    'code_postal' => '75002', 
                                    'departement' => '75'));
   
   private $mandataires = array('Mandataire-XXXXX' => 
                                array('nom' => 'mathurin',
                                    'carte-pro' => '7558754230',
                                    'adresse' => '226 rue de tolbiac'),
                                'Mandataire-XXXXY' => 
                                array('nom' => 'vincent',
                                    'carte-pro' => '7558454230',
                                    'adresse' => 'ailleurs'));
   
    public function configure()
    {
        $vendeurs = $this->getVendeurs();
        
        foreach ($vendeurs as $key => $value)
            $vendeursWidg[$key] = $value['cvi'];
        $this->setWidget('vendeur_identifiant', new sfWidgetFormChoice(array('choices' =>  $vendeursWidg)));
         
        
        $acheteurs = $this->getAcheteurs();
        
        foreach ($acheteurs as $key => $value)
                $acheteursWidg[$key] = $value['cvi'];
        $this->setWidget('acheteur_identifiant', new sfWidgetFormChoice(array('choices' =>  $acheteursWidg)));
    
        $mandataires = $this->getMandataires();
        
        foreach ($mandataires as $key => $value)
                $mandatairesWidg[$key] = $value['nom'].' '.$value['adresse'];
        
        $this->setWidget('mandataire_identifiant', new sfWidgetFormChoice(array('choices' =>  $mandatairesWidg)));
        
        $this->widgetSchema->setLabels(array(
            'vendeur_identifiant' => 'Sélectionner un vendeur ',
            'acheteur_identifiant' => 'Sélectionner un acheteur ',
            'mandataire_identifiant' => 'Sélectionner un mandataire '
        ));
        
        $this->setValidators(array(
            'vendeur_identifiant' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($vendeursWidg))),
            'acheteur_identifiant' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($acheteursWidg))),
            'mandataire_identifiant' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($mandatairesWidg)))
            ));
        $this->widgetSchema->setNameFormat('vrac[%s]');
    }
    
    public function getVendeurs()
    {
        return $this->vendeurs;
    }
    
    public function getAcheteurs() 
    {
        return $this->acheteurs;
    }
    
    public function getMandataires() 
    {
        return $this->mandataires;
    }
    
    public function doUpdateObject($values) 
    {
        parent::doUpdateObject($values);
    }
    
}
?>
