<?php

class factureGenerateRelancePdfTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'facture';
        $this->name             = 'generate-relance-pdf';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [testFacture|INFO] task does things.
Call it with:

    [php symfony facture:generate-relance-pdf|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

      $contextInstance = sfContext::createInstance($this->configuration);

      $items = FactureEtablissementView::getInstance()->getFactureNonPaye();
      $today = date('Y-m-d');
      $factureConf = FactureConfiguration::getInstance();
      $relances = array(array(), array());

      foreach($items as $item) {
          $dateFacture = $item->value[FactureEtablissementView::VALUE_DATE_FACTURATION];
          $facture = FactureClient::getInstance()->find($item->id);
          if ($facture->needRelance($factureConf->getDelaiRelance1(), 1)) {
              if (!isset($relances[0][$facture->identifiant])) {
                  $relances[0][$facture->identifiant] = array();
              }
              $relances[0][$facture->identifiant][] = $facture;
          }
          if ($facture->needRelance($factureConf->getDelaiRelance2(), 2)) {
              if (!isset($relances[1][$facture->identifiant])) {
                  $relances[1][$facture->identifiant] = array();
              }
              $relances[1][$facture->identifiant][] = $facture;
          }
      }
      foreach($relances as $index => $etablissements) {
        foreach($etablissements as $id => $factures) {
            $pdf = new FactureRelanceLatex($index+1, $factures);
        	$filename = $pdf->generate();
        }
      }

    }

}
