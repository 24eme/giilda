<?php

class DRMDroits extends BaseDRMDroits {

    const DROIT_CVO = 'CVO';
    const DROIT_DOUANE = 'Douane';

    public static $correspondanceGenreKey = array('TRANQ' => 'TRANQ', 'EFF' => 'MOUSSEUX', 'MOU' => 'MOUSSEUX');
    public static $correspondanceGenreLibelle = array('TRANQ' => 'Vins tranquille', 'EFF' => 'Vins mousseux', 'MOU' => 'Vins mousseux');

    public function getCumul() {
        $sum = 0;
        foreach ($this->toArray() as $key => $value) {
            $sum += $value->getCumul();
        }
        return $sum;
    }

    public function addDroitDouane($genreKey, $configurationCepageNode, $vol, $reintegration = false) {
        $keyDouane = self::$correspondanceGenreKey[$genreKey];
        $date = $this->getDocument()->getDate();
        $droitsConfig = $configurationCepageNode->getDroitByType($date, "INTERPRO-inter-loire", 'douane');

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
//        $genreDouaneNode->total = $vol;
//        $genreDouaneNode->report = $vol;
//        $genreDouaneNode->cumul = $vol;
    }

}
