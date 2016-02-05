<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProduitDatesCirculationCollectionForm
 *
 * @author mathurin
 */
class ProduitDatesCirculationCollectionForm extends BaseForm {

    public function configure() {
        if (!$dates_circulation = $this->getOption('dates_circulation'))
            throw new InvalidArgumentException('You must provide a dates_circulation node.');
       
        $key = 0;
        foreach ($dates_circulation as $date_circulation) {
            $this->embedForm($key, new ProduitDateCirculationForm(null, array('date_circulation' => $date_circulation)));
            $key++;
        }
        $newDateCirculation = $dates_circulation->add('', null);
        $this->embedForm($key, new ProduitDateCirculationForm(null, array('date_circulation' => $newDateCirculation)));
    }

}
