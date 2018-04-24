<?php

class DRMDroits extends BaseDRMDroits {

    const DROIT_CVO = 'CVO';
    const DROIT_DOUANE = 'Douane';

    public static $correspondanceGenreKey = array('TRANQ' => 'TRANQ', 'EFF' => 'MOUSSEUX', 'MOU' => 'MOUSSEUX', 'VCI' => 'VCI');
    public static $correspondanceGenreLibelle = array('TRANQ' => 'Vins tranquille', 'EFF' => 'Vins mousseux', 'MOU' => 'Vins mousseux', 'VCI' => 'Vins VCI');

    public function getCumul() {
        $sum = 0;
        foreach ($this->toArray() as $key => $value) {
            $sum += $value->getCumul();
        }
        return $sum;
    }

    public function initDroitsDouane() {
        $conf = ConfigurationClient::getCurrent();
        $date = $this->getDocument()->getDate();
        foreach ($conf->declaration->certifications as $keyCertif => $certification) {
            foreach ($certification->genres as $keyGenre => $genre) {
              if (isset(self::$correspondanceGenreKey[$keyGenre])) {
                $droitsDouaneConf = $genre->getDroitDouane($date);
                $droitDouane = $this->getOrAdd(self::$correspondanceGenreKey[$keyGenre]);
                $droitDouane->volume_reintegre = 0;
                $droitDouane->volume_taxe = 0;
                $droitDouane->taux = $droitsDouaneConf->taux;
                $droitDouane->code = $droitsDouaneConf->code;
                $droitDouane->libelle = self::$correspondanceGenreLibelle[$keyGenre];
                $droitDouane->updateTotal();
              }
            }
            return;
        }
    }

    public function updateDroitDouane($genreKey, $configurationCepageNode, $vol, $reintegration = false) {
        if($genreKey == "DEFAUT"){
            return;
        }
        $keyDouane = self::$correspondanceGenreKey[$genreKey];

        $date = $this->getDocument()->getDate();
        $droitsConfig = $configurationCepageNode->getDroitDouane($date);

        $genreDouaneNode = $this->getOrAdd($keyDouane);
        if ($reintegration) {
            $genreDouaneNode->volume_reintegre += $vol;
        } else {
            $genreDouaneNode->volume_taxe += $vol;
        }
        $genreDouaneNode->taux = $droitsConfig->taux;
        $genreDouaneNode->code = $droitsConfig->code;
        $genreDouaneNode->libelle = self::$correspondanceGenreLibelle[$genreKey];
        $genreDouaneNode->updateTotal();
    }

}
