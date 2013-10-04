<?php

class ConfigurationDroitsView extends acCouchdbView
{
	const KEY_HASH_PROD = 0;
	const KEY_TYPE = 1;
	const KEY_INTERRPRO = 2;
        
        
	const TYPE_LINE_PRODUITS = 'produits';
	const TYPE_LINE_LIEUX = 'lieux';
	const TYPE_LINE_LABELS = 'labels';

	public static function getInstance() {

        return acCouchdbManager::getView('configuration', 'droits', 'Configuration');
    }
    public function findCVOForProduct($hash,$interpro){
        $cvos_for_product = $this->findCVOsForProduct($hash, $interpro);
        $current_cvo_taux = null;
        $current_cvo_date = null;
        $date_of_day = date('Y-m-d');
        foreach ($cvos_for_product as $result_row) {
            $result_row_values = $result_row->value;
            foreach ($result_row_values as $productCVORows) {
                if(count($productCVORows)){
                    foreach ($productCVORows as $productNodeCVOs) {
                        if(is_null($current_cvo_date)){
                            $date = new DateTime($productNodeCVOs->date);
                            $current_cvo_date = $date->format('Y-m-d');
                            $current_cvo_taux = $productNodeCVOs->taux;
                        }else{
                            $date_row = new DateTime($productNodeCVOs->date);
                            $date_row = $date_row->format('Y-m-d');
                            if(($date_row <= $date_of_day) && ($date_row > $current_cvo_date)){
                                $current_cvo_date = $date_row;
                                $current_cvo_taux = $productNodeCVOs->taux;
                            }
                        }
                    }   
                }
            }
        }
        if(is_null($current_cvo_taux))
        {
            return 0;            
            //throw new sfException("Le produit de hash $hash ne possède aucun Droit CVO.");
        }
        return $current_cvo_taux;
    }
    
    public function findCVOsForProduct($hash,$interpro) {

    return $this->client->startkey(array($hash,'cvo', $interpro))
              				->endkey(array($hash,'cvo',$interpro, array()))
              				->getView($this->design, $this->view)->rows;
    }

    

}  