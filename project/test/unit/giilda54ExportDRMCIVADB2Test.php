<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

if($application != "civa") {
    $t = new lime_test(0);
    exit(0);
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

$preLigneCaveCSV = "CAVE;".$periode.";".$viti->identifiant.";";
$preLigneCRDCSV = "CRD;".$periode.";".$viti->identifiant.";";

$csv = <<<EOF
$preLigneCaveCSV;AOC;;AOC Alsace blanc;;;;Chasselas;;AOC Alsace blanc Chasselas (1B001S 5);suspendu;stocks_debut;initial;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace blanc;;;;Chasselas;;AOC Alsace blanc Chasselas (1B001S 5);suspendu;entrees;recolte;20;;;
$preLigneCaveCSV;AOC;;AOC Alsace blanc;;;;Chasselas;;AOC Alsace blanc Chasselas (1B001S 5);suspendu;sorties;export;10;DE;;
$preLigneCaveCSV;AOC;;AOC Alsace blanc;;;;Chasselas;;AOC Alsace blanc Chasselas (1B001S 5);suspendu;stocks_fin;final;90;;;
$preLigneCaveCSV;AOC;;AOC Alsace Lieu-dit;;;Blanc;Riesling;;AOC Alsace Lieu-dit Riesling (1B070S04);suspendu;stocks_debut;initial;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace Lieu-dit;;;Blanc;Riesling;;AOC Alsace Lieu-dit Riesling (1B070S04);suspendu;entrees;recolte;10;;;
$preLigneCaveCSV;AOC;;AOC Alsace Lieu-dit;;;Blanc;Riesling;;AOC Alsace Lieu-dit Riesling (1B070S04);suspendu;stocks_fin;final;110;;;
$preLigneCaveCSV;AOC;;AOC Alsace Communale;;Côte de Rouffach;Rouge;Pinot Noir;;AOC Alsace Communale Côte de Rouffach Pinot Noir (1R057S);suspendu;stocks_debut;initial;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace Communale;;Côte de Rouffach;Rouge;Pinot Noir;;AOC Alsace Communale Côte de Rouffach Pinot Noir (1R057S);suspendu;entrees;recolte;20;;;
$preLigneCaveCSV;AOC;;AOC Alsace Communale;;Côte de Rouffach;Rouge;Pinot Noir;;AOC Alsace Communale Côte de Rouffach Pinot Noir (1R057S);suspendu;stocks_fin;final;110;;;
$preLigneCaveCSV;AOC;;AOC Alsace Communale;;Vallée Noble;Blanc;Pinot Gris;;AOC Alsace Communale Vallée Noble Pinot Gris (1B062S03);suspendu;stocks_debut;initial;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace Communale;;Vallée Noble;Blanc;Pinot Gris;;AOC Alsace Communale Vallée Noble Pinot Gris (1B062S03);suspendu;entrees;recolte;10;;;
$preLigneCaveCSV;AOC;;AOC Alsace Communale;;Vallée Noble;Blanc;Pinot Gris;;AOC Alsace Communale Vallée Noble Pinot Gris (1B062S03);suspendu;stocks_fin;final;110;;;
$preLigneCaveCSV;AOC;;AOC Alsace Grand Cru;;Sommerberg;;Riesling;;AOC Alsace Grand Cru Sommerberg Riesling (1B039S 4);suspendu;stocks_debut;initial;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace Grand Cru;;Sommerberg;;Riesling;;AOC Alsace Grand Cru Sommerberg Riesling (1B039S 4);suspendu;entrees;recolte;10;;;
$preLigneCaveCSV;AOC;;AOC Alsace Grand Cru;;Sommerberg;;Riesling;;AOC Alsace Grand Cru Sommerberg Riesling (1B039S 4);suspendu;sorties;export;10;ES;;
$preLigneCaveCSV;AOC;;AOC Alsace Grand Cru;;Sommerberg;;Riesling;;AOC Alsace Grand Cru Sommerberg Riesling (1B039S 4);suspendu;stocks_fin;final;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace Grand Cru;VT;Sommerberg;;Pinot Gris;;AOC Alsace Grand Cru Sommerberg Pinot Gris VT (1B039D13);suspendu;stocks_debut;initial;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace Grand Cru;VT;Sommerberg;;Pinot Gris;;AOC Alsace Grand Cru Sommerberg Pinot Gris VT (1B039D13);suspendu;entrees;recolte;10;;;
$preLigneCaveCSV;AOC;;AOC Alsace Grand Cru;VT;Sommerberg;;Pinot Gris;;AOC Alsace Grand Cru Sommerberg Pinot Gris VT (1B039D13);suspendu;sorties;export;10;ES;;
$preLigneCaveCSV;AOC;;AOC Alsace Grand Cru;VT;Sommerberg;;Pinot Gris;;AOC Alsace Grand Cru Sommerberg Pinot Gris VT (1B039D13);suspendu;stocks_fin;final;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace Pinot noir;;;;Pinot Noir Rosé;;AOC Alsace Pinot noir (1S001S 1);suspendu;stocks_debut;initial;100;;;
$preLigneCaveCSV;AOC;;AOC Alsace Pinot noir;;;;Pinot Noir Rosé;;AOC Alsace Pinot noir (1S001S 1);suspendu;entrees;recolte;10;;;
$preLigneCaveCSV;AOC;;AOC Alsace Pinot noir;;;;Pinot Noir Rosé;;AOC Alsace Pinot noir (1S001S 1);suspendu;stocks_fin;final;110;;;
$preLigneCaveCSV;AOC;;AOC Crémant d'Alsace;;;;Blanc;;AOC Crémant d'Alsace Blanc (1B001M00);suspendu;stocks_debut;initial;100;;;
$preLigneCaveCSV;AOC;;AOC Crémant d'Alsace;;;;Blanc;;AOC Crémant d'Alsace Blanc (1B001M00);suspendu;entrees;recolte;10;;;
$preLigneCaveCSV;AOC;;AOC Crémant d'Alsace;;;;Blanc;;AOC Crémant d'Alsace Blanc (1B001M00);suspendu;stocks_fin;final;110;;;
$preLigneCRDCSV;Vert;TRANQ;Bouteille 75 cl;;;;;;;Banalisées suspendues;stock_debut;debut;100;;;
$preLigneCRDCSV;Vert;TRANQ;Bouteille 75 cl;;;;;;;Banalisées suspendues;entrees;achats;3;;;
$preLigneCRDCSV;Vert;TRANQ;Bouteille 75 cl;;;;;;;Banalisées suspendues;entrees;retours;2;;;
$preLigneCRDCSV;Vert;TRANQ;Bouteille 75 cl;;;;;;;Banalisées suspendues;entrees;excedents;1;;;
$preLigneCRDCSV;Vert;TRANQ;Bouteille 75 cl;;;;;;;Banalisées suspendues;sorties;utilisations;3;;;
$preLigneCRDCSV;Vert;TRANQ;Bouteille 75 cl;;;;;;;Banalisées suspendues;sorties;destructions;2;;;
$preLigneCRDCSV;Vert;TRANQ;Bouteille 75 cl;;;;;;;Banalisées suspendues;sorties;manquants;1;;;
$preLigneCRDCSV;Vert;TRANQ;Bouteille 75 cl;;;;;;;Banalisées suspendues;stock_fin;fin;100;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 75 cl;;;;;;;Banalisées suspendues;stock_debut;debut;100;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 75 cl;;;;;;;Banalisées suspendues;entrees;achats;3;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 75 cl;;;;;;;Banalisées suspendues;entrees;retours;2;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 75 cl;;;;;;;Banalisées suspendues;entrees;excedents;1;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 75 cl;;;;;;;Banalisées suspendues;sorties;utilisations;3;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 75 cl;;;;;;;Banalisées suspendues;sorties;destructions;2;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 75 cl;;;;;;;Banalisées suspendues;sorties;manquants;1;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 75 cl;;;;;;;Banalisées suspendues;stock_fin;fin;100;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 37,5 cl;;;;;;;Banalisées suspendues;stock_debut;debut;100;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 37,5 cl;;;;;;;Banalisées suspendues;entrees;achats;3;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 37,5 cl;;;;;;;Banalisées suspendues;entrees;retours;2;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 37,5 cl;;;;;;;Banalisées suspendues;entrees;excedents;1;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 37,5 cl;;;;;;;Banalisées suspendues;sorties;utilisations;3;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 37,5 cl;;;;;;;Banalisées suspendues;sorties;destructions;2;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 37,5 cl;;;;;;;Banalisées suspendues;sorties;manquants;1;;;
$preLigneCRDCSV;Bleu;MOUSSEUX;Bouteille 37,5 cl;;;;;;;Banalisées suspendues;stock_fin;fin;100;;;
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

$drm = DRMClient::getInstance()->find('DRM-'.$viti->identifiant.'-'.$periode);
$mouvements = $drm->mouvements->get($viti->identifiant);

$export = new ExportMouvementsDRMDB2();
$export->export($mouvements);
