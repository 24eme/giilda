<?php

class maintenanceRegionTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('document_id', sfCommandArgument::REQUIRED, 'Document ID to change'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'maintenance';
    $this->name             = 'region';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony maintenanceCompteStatut|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $this->document_id = $arguments['document_id'];
    $doc = acCouchdbManager::getClient()->find($this->document_id);
    if (!$doc) {
      throw new sfException('document '.$arguments['document_id'].' not found');
    }
    if ($doc->type == 'DRM') {
      $nregion = $this->getNewRegion($doc->region, $doc->declarant->code_postal);
      $doc->region = $nregion;
      $doc->declarant->region = $nregion;
    }elseif ($doc->type == 'Etablissement') {
      $doc->region = $this->getNewRegion($doc->region, $doc->siege->code_postal);
    }elseif( $doc->type == 'Vrac') {
      $doc->acheteur->region =  $this->getNewRegion($doc->acheteur->region, $doc->acheteur->code_postal);
      $doc->vendeur->region =  $this->getNewRegion($doc->vendeur->region, $doc->vendeur->code_postal);
    }else{
      return ;
    }
    echo "Region changed for ".$doc->_id."\n";
    $doc->save();
  }

  private function getNewRegion($oldregion, $codepostal) {
      $dep = substr(sprintf('%05d', $codepostal), 0, 2);
      $preregion = '';
      if (in_array($dep, array('85', '44', '49', '79', '86'))) {
        $preregion = 'PDL';
      }elseif(in_array($dep, array('72', '37', '41', '36', '45', '18', '58', '03', '63'))) {
        $preregion = 'CENTRE';
      }else{
        if ($oldregion == EtablissementClient::REGION_HORSINTERLOIRE)
          return EtablissementClient::REGION_HORS_REGION;
      }
      if (!$preregion) {
        throw new sfException($this->document_id.' : Strange region from '.$codepostal.' - '.$oldregion);
      }
      $postregion = 'IGP';
      if ($oldregion != EtablissementClient::REGION_HORSINTERLOIRE) {
        $postregion = 'AOP';
      }
      if (
          ($oldregion == 'TOURS' && $preregion != 'CENTRE') ||
          (($oldregion == 'NANTES' && $oldregion == 'ANGERS') && $preregion != 'PDL')
      ) {
        throw new sfException($this->document_id.' : Cas étrange : '.$codepostal.' - '.$oldregion.' => '.$preregion.'_'.$postregion);
      }
      return $preregion.'_'.$postregion;
  }

}
