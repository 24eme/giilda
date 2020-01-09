<?php

class SV12Client extends acCouchdbClient {
    /**
     *
     * @return DRMClient
     */

    const STATUT_VALIDE = 'VALIDE'; 
    const STATUT_VALIDE_PARTIEL = 'VALIDE_PARTIEL'; 
    const STATUT_BROUILLON = 'BROUILLON'; 
    
    const SV12_KEY_SANSCONTRAT = 'SANSCONTRAT'; 
    const SV12_KEY_SANSVITI = 'SANSVITI'; 
    
    const SV12_VIEWHISTORY_ID = 0;
    const SV12_VIEWHISTORY_DATESAISIE = 1;    
    const SV12_VIEWHISTORY_PERIODE = 2;
    const SV12_VIEWHISTORY_VERSION = 3;     
    const SV12_VIEWHISTORY_NEGOCIANT_ID = 4;
    const SV12_VIEWHISTORY_NEGOCIANT_NOM = 5;
    const SV12_VIEWHISTORY_NEGOCIANT_CVI = 6;
    const SV12_VIEWHISTORY_NEGOCIANT_COMMUNE = 7;
    const SV12_VIEWHISTORY_STATUT = 8;

    public static function getInstance()
    {
      return acCouchdbManager::getClient("SV12");
    }

    public function buildId($identifiant, $periode, $version = null) {

      return 'SV12-'.$identifiant.'-'.$this->buildPeriodeAndVersion($periode, $version);
    }

    public function buildDate($periode) {
      if(!preg_match('/([0-9]{4})-([0-9]{4})/', $periode, $matches)) {

        throw new sfException(sprintf('Pas au format yyyy-yyyy %s', $periode));
      }

      return sprintf('%4d-%02d-%02d', $matches[1], 12, 31);
    }

    public function buildPeriode($date) {
      
        return ConfigurationClient::getInstance()->buildCampagne($date);
    }

    public function buildPeriodeAndVersion($periode, $version) {
      if($version) {
        return sprintf('%s-%s', $periode, $version);
      }

      return $periode;
    }

    public function buildPeriodeFromCampagne($campagne) {

      return $campagne;
    }

    public function buildCampagne($periode) {

    	return $periode;
    }
    
    public function createOrFind($identifiant, $periode) {
        $sv12 = $this->find($this->buildId($identifiant, $periode));
        if (!$sv12) {
          $sv12 = new Sv12();
          $sv12->identifiant = $identifiant;
          $sv12->periode = $periode;
          $sv12->valide->statut = SV12Client::STATUT_BROUILLON;
        }

        return $sv12;
    }

    public function getMaster($id) {
        $matches = array();
        $sv12_master = null;
        if(preg_match('/^SV12-([0-9]{8})-([0-9]{4}-[0-9]{4})*/', $id, $matches)){
            $identifiant = $matches[1];
            $periode = $matches[2];
            $sv12_master = $this->findMaster($identifiant, $periode);
        }
        if(!$sv12_master){
            throw new sfException("La SV12 master avec l'id $id n'a pas été trouvée.");
        }
        return $sv12_master;
    }
    
    public function findMaster($id_or_identifiant, $periode) {

      $sv12 = SV12AllView::getInstance()->getMasterByEtablissementAndPeriode($id_or_identifiant, $periode);

      if (!$sv12) {
        return;
      }

      return $this->find($sv12->_id);
    }

    public function retreiveSV12s() {   
      return SV12AllView::getInstance()->findAll();
        
    }
    
    public function findMasterRectificative($id_or_identifiant, $periode, $version_rectificative) {

      $sv12 = SV12AllView::getInstance()->getMasterByEtablissementPeriodeAndVersionRectificative($id_or_identifiant, $periode, $version_rectificative);

      if (!$sv12) {
        return;
      }

      return $this->find($sv12->_id);
    }
    
    
    public function findContratsByEtablissement($identifiant) {   
      return $this->findContratsByEtablissementAndCampagne($identifiant, ConfigurationClient::getInstance()->getCurrentCampagne());
        
    }
    
    public function findContratsByEtablissementAndCampagne($identifiant, $campagne) {   
      return VracClient::getInstance()->retrieveBySoussigneAndType($identifiant,  $campagne, array('start' => VracClient::TYPE_TRANSACTION_MOUTS, 'end' => VracClient::TYPE_TRANSACTION_RAISINS), 10000)->rows;    
    }
    
    public function getLibelleFromId($id) {

        if (!preg_match('/^SV12-[0-9]+-([0-9]{4})-([0-9]{4})/', $id, $matches)) {
            
            return $id;
        }

        return sprintf('SV12 de %s',  $matches[1]);
    }

    public function getSV12CSVArray($sv12) {
        $url = $sv12->getSV12DouaneURL();
        if (!$url) {
            return array();
        }
        $tempfname = tempnam("/tmp", "sv12xls_").".xls";
        $temp = fopen($tempfname, "w");
        fwrite($temp, file_get_contents($url));
        fclose($temp);
        exec("bash ".sfConfig::get('app_sv12_path2odgproject')."/bin/get_csv_dr_from_douane.sh $tempfname", $output);
        $sv12 = array();
        foreach($output as $line) {
            $csv = str_getcsv($line, ';');
            $csv[21] = preg_replace('/,/', '.', $csv[21]) * 1;
            if (($csv[19] == '11' || $csv[19] == '10') && $csv[9]) {
                if (!isset($sv12[$csv[22]])) {
                    $sv12[$csv[22]] = array();
                }
                $sv12_hash = '/declaration/certifications/'.$csv[9].'/genres/'.$csv[10].'/appellations/'.$csv[11].'/mentions/'.$csv[12].'/lieux/'.$csv[13].'/couleurs/'.$csv[14].'/cepages/'.$csv[15];
                if (!isset($sv12[$csv[22]])) {
                    $sv12[$csv[22]] = array();
                }
                if (!isset($sv12[$csv[22]][$sv12_hash.$csv[19]])) {
                    $sv12[$csv[22]][$sv12_hash.$csv[19]] = array();
                }
                $csv[] = $sv12_hash;
                $sv12[$csv[22]][$sv12_hash.$csv[19]][] = $csv;
            }
        }
        return $sv12;
    }

}
