<?php
require_once(dirname(__FILE__).'/../bootstrap/common.php');

$project_dir = sfConfig::get('sf_apps_dir')."/../";
$drm_dir = sfConfig::get('sf_apps_dir')."/../plugins/acVinDRMPlugin";
$task_filename = sfConfig::get('sf_apps_dir')."/../plugins/acVinDRMPlugin/lib/task/DRMControlesTask.class.php";

$t = new lime_test(4);

$drm_test_coherence = 'DRM-00561601-201902';
$drm_test_erreur_transmission = "DRM-00511701-201909";
$drm_test_vigilance = "DRM-00233501-201901";
$app = 'ivbd';

$keys_result = ["identifiant", "erreur","engagement", "vigilance", "transmission", "coherence"];

$res_string = shell_exec("php $project_dir/symfony drm:controles --application=$app $drm_test_coherence");
$res_array = explode(";", $res_string);
$res = array_combine($keys_result, $res_array);

$t->is((int)explode(":", $res["coherence"])[1],1, "coherence : control fund $drm_test_coherence");

$res_string = shell_exec("php $project_dir/symfony drm:controles --application=$app $drm_test_erreur_transmission");
$res_array = explode(";", $res_string);
$res = array_combine($keys_result, $res_array);

$t->is((int)explode(":", $res["erreur"])[1],1, "errreur : control fund $drm_test_erreur_transmission");
$t->is((int)explode(":", $res["transmission"])[1],1, "transmission : control fund $drm_test_erreur_transmission");

$res_string = shell_exec("php $project_dir/symfony drm:controles --application=$app $drm_test_vigilance");
$res_array = explode(";", $res_string);
$res = array_combine($keys_result, $res_array);

$t->is((int)explode(":", $res["vigilance"])[1],1, "vigilance : control fund $drm_test_vigilance");


