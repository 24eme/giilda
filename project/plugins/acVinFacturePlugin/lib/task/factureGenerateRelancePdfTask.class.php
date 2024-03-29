<?php

class factureGenerateRelancePdfTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addArguments(array(
    			    new sfCommandArgument('relancesCsv', null, sfCommandOption::PARAMETER_REQUIRED, 'Csv des relances'),
                    new sfCommandArgument('numRelance', null, sfCommandOption::PARAMETER_REQUIRED, 'Numero de relance'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('filename', null, sfCommandOption::PARAMETER_REQUIRED, 'Nom du fichier'),
            new sfCommandOption('directory', null, sfCommandOption::PARAMETER_REQUIRED, 'Output directory'),
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
      sfContext::createInstance($this->configuration);
      if(!$options['application']){
        throw new sfException("Le choix de l'application est obligatoire");

      }
      // Organise par relance et etablissement
      $relances = array();
      $infos = array();
      $interpro = array();
      foreach (file($arguments['relancesCsv']) as $ligne) {
          $datas = explode(';', $ligne);
          if (strpos($datas[17], 'FACTURE-') === false) continue;
          if ($arguments['numRelance'] != $datas[2]) continue;
          $index = $datas[1].'_'.$datas[3];
          if (!isset($relances[$index])) {
              $relances[$index] = array();
          }
          if (!isset($infos[$index])) {
              $infos[$index] = $this->getSocieteInfosObject($datas);
          }
          $relances[$index][] = $datas;
          if (isset($datas[18])) {
              $interpro[$datas[18]] = $datas[18];
          }
      }
      if (count($interpro) > 1) throw new Exception('Les relances facture doivent être mono interpro');
      $interpro = (!$interpro)? null : trim(array_key_first($interpro));
      $hasPdf = false;
      foreach($relances as $key => $items) {
      	$pdf = new FactureRelanceLatex($infos[$key], $items, str_replace('.pdf', "_$key", $options['filename']), $interpro);
      	$path = $pdf->generatePDF();
        $destdir = $options['directory'].'/'.$pdf->getPublicFileName();
        copy($path, $destdir) or die("pb rename $path $destdir");
        $hasPdf = true;
      }
      if ($hasPdf) exec('pdftk '.str_replace('.pdf', "*.pdf", $options['directory'].'/'.$options['filename']).' cat output '.$options['directory'].'/'.$options['filename']);
    }

    private function getSocieteInfosObject($datas) {
        return (object) array(
                'id_relance' => $datas[1],
                'nb_relance' => $datas[2],
                'date_relance' => $datas[0],
                'identifiant' => $datas[3],
                'code_comptable' => $datas[11],
                'raison_sociale' => $datas[4],
                'nom' => $datas[4],
                'adresse' => $datas[5],
                'adresse_complementaire' => $datas[6],
                'code_postal' => $datas[8],
                'commune' => $datas[7],
                'date_derniere_relance' => $datas[16]
        );
    }

}
