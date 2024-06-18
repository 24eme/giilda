<?php

class DRMTransfertReserveInterproForm extends acCouchdbForm
{
    public $produits = [];

    public function configure()
    {
        $drm = $this->getDocument();
        foreach ($drm->getProduitsReserveInterpro() as $produit) {
            $key = 'reserve_interpro_'.$produit->getHash();

            $this->setWidget($key, new bsWidgetFormInputFloat([], ['placeholder' => 'hl', 'class' => 'form-control text-right input-float']));
            $this->widgetSchema->setLabel($key, $produit->getLibelle());
            $this->setValidator($key, new sfValidatorNumber(array('required' => true)));
            $this->setDefault($key, $produit->reserve_interpro);

            $this->produits[] = $key;
        }

        $this->widgetSchema->setNameFormat('reserveinterpro[%s]');
    }

    public function save()
    {
        $values = $this->getValues();
        $drm = $this->getDocument();

        foreach ($values as $key => $value) {
            if (strpos($key, 'reserve_interpro_') !== 0) {
                continue;
            }

            $produit = $drm->get(
                str_replace('reserve_interpro_', '', $key)
            );
            $produit->reserve_interpro = $value;
        }

        $drm->save();
    }

    public function getProduits()
    {
        return $this->produits;
    }
}
