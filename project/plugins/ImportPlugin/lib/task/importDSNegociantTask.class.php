<?php

class importDSNegociantTask extends importDSTask
{

  const CSV_VOLUME_VRAC = parent::CSV_VOLUME_1;
  const CSV_VOLUME_BOUTEILLE = parent::CSV_VOLUME_2;

  protected function configure()
  {
    parent::configure();
    $this->name = 'ds-negociant';
  }

  public function importLigne($ds, $line) {
    $ds = parent::importLigne($ds, $line);

    if(!$ds->getEtablissementObject()->isNegociant()) {
      throw new sfException(sprintf("L'etablissement %s n'est pas un NEGOCIANT : %s", $ds->getEtablissementObject()->_id, $ds->getEtablissementObject()->famille));
    }

    if(!$this->hasNumeroLigne($line)) {

      return $ds;
    }

    $config_produit = $this->getConfigurationHash($line[self::CSV_CODE_APPELLATION]);

    $produit = $ds->addProduit($config_produit->getHash());
    $produit->stock_declare = $this->convertToFloat($line[self::CSV_VOLUME_VRAC] + $line[self::CSV_VOLUME_BOUTEILLE]);

    return $ds;
  }

  protected function getDateCreation($line) {
    $date_creation = $this->convertToDateObject($line[self::CSV_DATE_CREATION])->format('Y-m-d');
    $date_stock_fevrier = $this->getDateCreationFevrier($line);
    $date_stock_juillet = $this->getDateCreationJuillet($line);

    if($date_creation < $date_stock_juillet)  {

      return $date_stock_fevrier;
    }


    return $date_stock_juillet;
  }


}
