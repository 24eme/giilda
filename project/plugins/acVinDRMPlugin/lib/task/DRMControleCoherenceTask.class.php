<?php

class DRMControleCoherenceTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
           new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, "Identifiant de l'établissement"),
        ));

        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'drm';
        $this->name             = 'controle-coherence';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
    The [importVrac|INFO] task does things.
    Call it with:

    [php symfony import:drm|INFO]
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
      
        $etablissementIdentifiant = $arguments['identifiant'];
        $societeIdentifiant = preg_replace("/[0-9]{2}$/", "", $etablissementIdentifiant);
        $campagneDebut = "1990-1991";
        $campagneFin = ConfigurationClient::getInstance()->getCurrentCampagne();
        $campagnes = array();
        $campagne = $campagneDebut;
        while($campagne != ConfigurationClient::getInstance()->getNextCampagne($campagneFin)) {
            $campagnes[$campagne] = $campagne;
            $campagne = ConfigurationClient::getInstance()->getNextCampagne($campagne);
        }

        $stocks_fin = array();
        foreach($campagnes as $campagne) {
            $drms = DRMStocksView::getInstance()->findByCampagneAndEtablissement($campagne, null, $etablissementIdentifiant);
            foreach($drms as $key => $drmProduit) {
                if(!isset($stocks_fin[$drmProduit->produit_hash])) {
                    $stocks_fin[$drmProduit->produit_hash] = $drmProduit;
                    continue;
                }

                $drmProduitPrevious = $stocks_fin[$drmProduit->produit_hash];
                $periodeSuivante = ConfigurationClient::getInstance()->getPeriodeSuivante($drmProduitPrevious->periode);

                if($drmProduit->periode != $periodeSuivante) {
                    echo sprintf("Le produit n'existe pas pour cette période : #%s;%s;%s\n", $drmProduit->etablissement_identifiant, $periodeSuivante, $drmProduit->produit_libelle);
                    $stocks_fin[$drmProduit->produit_hash] = $drmProduit;
                    continue;
                }

                if(round($drmProduitPrevious->volume_stock_fin_mois, 2) != round($drmProduit->volume_stock_debut_mois, 2)) {
                    echo sprintf("Le stock fin de mois et le stock début de mois ne corresondent pas : #%s;%s/%s;%0.2f/%0.2f;%s\n", $drmProduit->etablissement_identifiant, $drmProduitPrevious->periode, $drmProduit->periode, $drmProduitPrevious->volume_stock_fin_mois, $drmProduit->volume_stock_debut_mois, $drmProduit->produit_libelle);
                    $stocks_fin[$drmProduit->produit_hash] = $drmProduit;
                    continue;
                }

                $stocks_fin[$drmProduit->produit_hash] = $drmProduit;
            }
        }
    }

}
