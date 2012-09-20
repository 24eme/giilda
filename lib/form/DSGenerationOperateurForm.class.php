<?php
/**
 * Description of class StockGenerationDeclarantForm
 * @author mathurin
 */
class DSGenerationOperateurForm extends BaseForm {

    private $anneeCampagneStart = 1991;
    
    public function configure()
    {
        $this->setWidget('campagne', new sfWidgetFormChoice(array('choices' => $this->getCampagnes())));     
        $this->setWidget('date_declaration', new sfWidgetFormInput());
        
        
        $this->widgetSchema->setLabels(array(
            'campagne' => 'Sélectionner la campagne viticole :',
            'date_declaration' => 'Déclarations relatives aux stocks de vin au :'
        ));
        $this->widgetSchema->setNameFormat('ds_generation[%s]');
    }

    public function getCampagnes() {
        $annee = date('Y');
        $anneePlusUn = $annee + 1;
        
        $campagnes = array();
        for ($currentA = $anneePlusUn; $currentA > $this->anneeCampagneStart; $currentA--) {
            $elt = $currentA.'-'.($currentA+1);
            $campagnes[$elt] = $elt;
        }
        return $campagnes;
    }

}

?>
