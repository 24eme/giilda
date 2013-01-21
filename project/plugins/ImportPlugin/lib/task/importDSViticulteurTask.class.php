<?php

class importDSViticulteurTask extends importDSTask
{

  const CSV_VOLUME_LIBRE = parent::CSV_VOLUME_1;
  const CSV_VOLUME_BLOQUE = parent::CSV_VOLUME_2;

  protected function configure()
  {
    parent::configure();
    $this->name = 'ds-viticulteur';
  }

  public function importLigne($ds, $line) {
    $ds = parent::importLigne($ds, $line);

    if(!$ds->getEtablissementObject()->isViticulteur()) {
      throw new sfException(sprintf("L'etablissement %s n'est pas un PRODUCTEUR : %s", $ds->getEtablissementObject()->_id, $ds->getEtablissementObject()->famille));
    }

    if(!$this->hasNumeroLigne($line)) {

      return $ds;
    }

    $config_produit = $this->getConfigurationHash($line[self::CSV_CODE_APPELLATION]);

    $produit = $ds->addProduit($config_produit->getHash());
    $produit->stock_declare = $this->convertToFloat($line[self::CSV_VOLUME_LIBRE]);
    $produit->vci = $this->convertToFloat($line[self::CSV_VOLUME_BLOQUE]);

    return $ds;
  }

  protected function getDateCreation($line) {

    return $this->getDateCreationJuillet($line);
  }
}