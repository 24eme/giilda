<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$t = new lime_test(9);
$t->comment('suppression des différentes sociétés, de leurs établissements et comptes');

$clientcond = false;
foreach (CompteTagsView::getInstance()->listByTags('test', 'test') as $k => $v) {
    if (preg_match('/SOCIETE-([^ ]*)/', implode(' ', array_values($v->value)), $m)) {
      $soc = SocieteClient::getInstance()->findByIdentifiantSociete($m[1]);
      foreach($soc->getEtablissementsObj() as $k => $etabl) {
          foreach (VracClient::getInstance()->retrieveBySoussigne($etabl->etablissement->identifiant)->rows as $k => $vrac) {
            $vrac_obj = VracClient::getInstance()->find($vrac->id);
            $vrac_obj->delete();
            $t->is(VracClient::getInstance()->find($vrac->id), null, "Suppression du contrat ".$vrac->id);
          }
          foreach (DRMClient::getInstance()->viewByIdentifiant($etabl->etablissement->identifiant) as $id => $drm) {
            $drm = DRMClient::getInstance()->find($id);
            $drm->delete();
            $t->is(DRMClient::getInstance()->find($id), null, "Suppression de la DRM ".$id);
          }
      }
      $soc->delete();
      $t->is(CompteClient::getInstance()->findByIdentifiant($m[1].'01'), null, "Suppression de la sociétés ".$m[1]." provoque la suppression de son compte");
    }
}
