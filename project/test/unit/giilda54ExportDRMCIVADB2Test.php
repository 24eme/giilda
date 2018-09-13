<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

if($application != "civa") {
    exit;
}

$viti = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$societe = $viti->getSociete();
$periode = date('Ym');

//Suppression des DRM précédentes
foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
  $csv = CSVDRMClient::getInstance()->find(str_replace("DRM", "CSVDRM", $k));
  $csv->delete(false);
}

$preLigneCSV = "CAVE;".$periode.";".$viti->identifiant.";";

$csv = <<<EOF
$preLigneCSV;AOC;;AOC Alsace blanc;;;;Chasselas;;AOC Alsace blanc Chasselas (1B001S 5);suspendu;stocks_debut;initial;100;;;
$preLigneCSV;AOC;;AOC Alsace blanc;;;;Chasselas;;AOC Alsace blanc Chasselas (1B001S 5);suspendu;entrees;recolte;20;;;
$preLigneCSV;AOC;;AOC Alsace blanc;;;;Chasselas;;AOC Alsace blanc Chasselas (1B001S 5);suspendu;stocks_fin;final;110;;;
$preLigneCSV;AOC;;AOC Alsace Lieu-dit;;;Blanc;Riesling;;AOC Alsace Lieu-dit Riesling (1B070S04);suspendu;stocks_debut;initial;100;;;
$preLigneCSV;AOC;;AOC Alsace Lieu-dit;;;Blanc;Riesling;;AOC Alsace Lieu-dit Riesling (1B070S04);suspendu;entrees;recolte;10;;;
$preLigneCSV;AOC;;AOC Alsace Lieu-dit;;;Blanc;Riesling;;AOC Alsace Lieu-dit Riesling (1B070S04);suspendu;stocks_fin;final;110;;;
$preLigneCSV;AOC;;AOC Alsace Communale;;Côte de Rouffach;Rouge;Pinot Noir;;AOC Alsace Communale Côte de Rouffach Pinot Noir (1R057S);suspendu;stocks_debut;initial;100;;;
$preLigneCSV;AOC;;AOC Alsace Communale;;Côte de Rouffach;Rouge;Pinot Noir;;AOC Alsace Communale Côte de Rouffach Pinot Noir (1R057S);suspendu;entrees;recolte;20;;;
$preLigneCSV;AOC;;AOC Alsace Communale;;Côte de Rouffach;Rouge;Pinot Noir;;AOC Alsace Communale Côte de Rouffach Pinot Noir (1R057S);suspendu;stocks_fin;final;110;;;
$preLigneCSV;AOC;;AOC Alsace Communale;;Vallée Noble;Blanc;Pinot Gris;;AOC Alsace Communale Vallée Noble Pinot Gris (1B062S03);suspendu;stocks_debut;initial;100;;;
$preLigneCSV;AOC;;AOC Alsace Communale;;Vallée Noble;Blanc;Pinot Gris;;AOC Alsace Communale Vallée Noble Pinot Gris (1B062S03);suspendu;entrees;recolte;10;;;
$preLigneCSV;AOC;;AOC Alsace Communale;;Vallée Noble;Blanc;Pinot Gris;;AOC Alsace Communale Vallée Noble Pinot Gris (1B062S03);suspendu;stocks_fin;final;110;;;
$preLigneCSV;AOC;;AOC Alsace Grand Cru;;Sommerberg;;Riesling;;AOC Alsace Grand Cru Sommerberg Riesling (1B039S 4);suspendu;stocks_debut;initial;100;;;
$preLigneCSV;AOC;;AOC Alsace Grand Cru;;Sommerberg;;Riesling;;AOC Alsace Grand Cru Sommerberg Riesling (1B039S 4);suspendu;entrees;recolte;10;;;
$preLigneCSV;AOC;;AOC Alsace Grand Cru;;Sommerberg;;Riesling;;AOC Alsace Grand Cru Sommerberg Riesling (1B039S 4);suspendu;stocks_fin;final;110;;;
$preLigneCSV;AOC;;AOC Alsace Grand Cru;VT;Sommerberg;;Pinot Gris;;AOC Alsace Grand Cru Sommerberg Pinot Gris VT (1B039D13);suspendu;stocks_debut;initial;100;;;
$preLigneCSV;AOC;;AOC Alsace Grand Cru;VT;Sommerberg;;Pinot Gris;;AOC Alsace Grand Cru Sommerberg Pinot Gris VT (1B039D13);suspendu;entrees;recolte;10;;;
$preLigneCSV;AOC;;AOC Alsace Grand Cru;VT;Sommerberg;;Pinot Gris;;AOC Alsace Grand Cru Sommerberg Pinot Gris VT (1B039D13);suspendu;stocks_fin;final;110;;;
$preLigneCSV;AOC;;AOC Alsace Pinot noir;;;;Pinot Noir Rosé;;AOC Alsace Pinot noir (1S001S 1);suspendu;stocks_debut;initial;100;;;
$preLigneCSV;AOC;;AOC Alsace Pinot noir;;;;Pinot Noir Rosé;;AOC Alsace Pinot noir (1S001S 1);suspendu;entrees;recolte;10;;;
$preLigneCSV;AOC;;AOC Alsace Pinot noir;;;;Pinot Noir Rosé;;AOC Alsace Pinot noir (1S001S 1);suspendu;stocks_fin;final;110;;;
$preLigneCSV;AOC;;AOC Crémant d'Alsace;;;;Blanc;;AOC Crémant d'Alsace Blanc (1B001M00);suspendu;stocks_debut;initial;100;;;
$preLigneCSV;AOC;;AOC Crémant d'Alsace;;;;Blanc;;AOC Crémant d'Alsace Blanc (1B001M00);suspendu;entrees;recolte;10;;;
$preLigneCSV;AOC;;AOC Crémant d'Alsace;;;;Blanc;;AOC Crémant d'Alsace Blanc (1B001M00);suspendu;stocks_fin;final;110;;;
EOF;

$tmpfname = tempnam("/tmp", "DRM_");
$temp = fopen($tmpfname, "w");

fwrite($temp, $csv);
fclose($temp);

$t = new lime_test(1);

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode);
$import = new DRMImportCsvEdi($tmpfname, $drm);
$t->ok($import->checkCSV(), "Vérification de l'import");
$t->ok($import->importCSV(),"Import de la DRM");

$drm->validate();
$drm->save();

/*$mouvements = MouvementfactureFacturationView::getInstance()->getMouvements(0, array(0, 1), MouvementfactureFacturationView::KEYS_MVT_TYPE + 1);
$mouvementsDRM = array();
foreach($mouvements as $mouvement) {
    if($id_doc != "DRM-".$viti->identifiant."-".$periode) {
        continue;
    }
}*/

$drm = DRMClient::getInstance()->find('DRM-'.$viti->identifiant.'-'.$periode);

$mouvements = $drm->mouvements->get($viti->identifiant);

$correspondances = array(
    "entrees/recolte"                    => "01.DRMDEM/06.Entrées",
    "entrees/achatnoncrd"                => "01.DRMDEM/10.Achats vrac + bouteilles sans CRD (Propriété)",
    "entrees/achatnoncrd/NEGOCE"         => "01.DRMDEM/14.Achats vrac + bouteilles sans CRD (Négociant)",
    "entrees/retourmarchandisetaxees"    => "01.DRMDEM/18.Quantités réintégrées CVO + Droits circulation 12a",
    "entrees/retourmarchandisenontaxees" => "01.DRMDEM/22.Quantités réintégrées CVO seule 12b",
    "entrees/repli"                      => "01.DRMDEM/26.Replis",
    "sorties/vracsanscontratsuspendu"    => "02.DRMDSS/06.B - Hors région Alsace (UE - pays tiers ou autre EA en France)",
    "sorties/vrac"                       => "02.DRMDSS/10.C - Vrac",
    "sorties/bouteillenue"               => "02.DRMDSS/14.D - Expeditions en Alsace en bouteilles",
    "sorties/"                           => "03.DRMDSE/06.I - Vers un utilisateur autorisé",
    "sorties/"                           => "03.DRMDSE/10.J - Dégustations à la propriété",
    "sorties/"                           => "04.DRMDS0/06.K - Replis",
    "sorties/"                           => "04.DRMDSO/10.L - Lies",
    "sorties/"                           => "05.DRMDSA/06.A - (75 cl) CRD ou DS/DSAC France",
    "sorties/"                           => "05.DRMDSA/10.A - CRD ou DS/DSAC France",
    "sorties/"                           => "05.DRMDSA/14.A bis - DSA/DSAC Hors France Métropolitaine",
);

function convertProduitDB2($hash) {
    if(!preg_match('#/AOC_ALSACE#', $hash)) {

        return null;
    }

    if(preg_match('#/GRDCRU#', $hash)) {

        return "03.GRDCRU";
    }

    if(preg_match("#/CREMANT#", $hash)) {

        return "04.CREMANT";
    }

    if(preg_match('#/(PN|PR|rouge)#', $hash)) {

        return "02.ROUGE";
    }

    return "01.BLANC";
}

function convertMouvementDB2($typeHash, $correspondances) {
    if(!isset($correspondances[$typeHash])) {

        return null;
    }

    return $correspondances[$typeHash];
}

$db2 = array();

foreach($mouvements as $mouvement) {
    $produit = convertProduitDB2($mouvement->produit_hash);
    $mouvementType = convertMouvementDB2($mouvement->type_hash, $correspondances);
    $identifiantPeriode = $drm->identifiant."-".$drm->periode;
    if(!$produit) {
        continue;
    }
    if(!$mouvementType) {
        continue;
    }
    if(!isset($db2[$identifiantPeriode])) {
        $db2[$identifiantPeriode] = array();
    }
    if(!isset($db2[$identifiantPeriode][$produit])) {
        $db2[$identifiantPeriode][$produit] = array();
    }
    if(!isset($db2[$identifiantPeriode][$produit][$mouvementType])) {
        $db2[$identifiantPeriode][$produit][$mouvementType] = 0;
    }

    $db2[$identifiantPeriode][$produit][$mouvementType] += $mouvement->volume;
    ksort($db2[$identifiantPeriode][$produit]);

    print_r($mouvement->toArray(true, false));
}

print_r($db2);
