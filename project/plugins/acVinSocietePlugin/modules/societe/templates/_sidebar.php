<div class="panel panel-default" style="margin-bottom: 10px;">
    <div class="panel-heading"><h3 class="panel-title"><span class="glyphicon glyphicon-calendar"></span> Société</h3></div>
    <div class="list-group">
        <div class="list-group-item clearfix <?php if($societe->_id == $activeObject->_id): ?>active-bordered<?php endif; ?>">
            <?php include_partial('societe/bloc', array('societe' => $societe)); ?>
        </div>
    </div>
</div>
<div class="panel panel-default" style="margin-bottom: 10px;">
    <div class="panel-heading"><h3 class="panel-title"><span class="glyphicon glyphicon-home"></span> Établissements</h3></div>
    <div class="list-group">
        <?php foreach($etablissements as $etablissement): ?>
            <div class="list-group-item clearfix <?php if($etablissement->_id == $activeObject->_id): ?>active-bordered<?php endif; ?>">
                <?php include_partial('etablissement/bloc', array('etablissement' => $etablissement)); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if($modifiable): ?>
    <div class="panel-footer text-center">
        <a class="btn btn-xs btn-link" href="<?php echo url_for('etablissement_ajout', array('identifiant' => $societe->identifiant)); ?>"><span class="glyphicon glyphicon-plus-sign"></span> Créer un établissement</a>
    </div>
    <?php endif; ?>
</div>
<?php
  $points = array();
  if($societe->_id == $activeObject->_id){
    $compteSociete = $societe->getMasterCompte();
    $points = array_values($compteSociete->getRawValue()->getCoordonneesLatLon());
  }
  foreach($etablissements as $etablissement){
    if($etablissement->_id == $activeObject->_id){
      $compteEtb = $etablissement->getMasterCompte();
      $points = array_values($compteEtb->getRawValue()->getCoordonneesLatLon());
      if($etablissement->exist('chais') && $etablissement->chais){
        $chaiLatLon = array();
        foreach ($etablissement->chais as $chai) {
          if($chai->lat && $chai->lon){
            $chaiLatLon[$chai->lat.$chai->lon] = array($chai->lat, $chai->lon);
          }
        }
        $points = array_merge($points,array_values($chaiLatLon));
      }
    }
  }
  foreach ($interlocuteurs as $interlocuteurId => $interlorcuteur){
    if($interlorcuteur->_id == $activeObject->_id){
      $points = array_values($interlorcuteur->getRawValue()->getCoordonneesLatLon());
    }
  }
 ?>
<div class="carte" data-point='<?php echo json_encode($points) ?>'  style="height: 180px; border-radius: 4px; margin-bottom: 10px;"></div>
<?php if (isset($needUpdateLatLon) && $needUpdateLatLon): ?>
    <div class="text-center panel-footer" style="margin-top: -10px; margin-bottom: 10px;">
        <a href="<?php echo url_for('etablissement_update_coordonnees_latlon', $etablissement) ?>"><i class="glyphicon glyphicon-refresh"></i> Mettre à jour les coordonnées</a>
    </div>
<?php endif ?>
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Interlocuteurs</h3></div>
    <?php if(count($interlocuteurs)): ?>
    <div class="list-group">
        <?php foreach ($interlocuteurs as $interlocuteurId => $interlorcuteur) : ?>
            <div class="list-group-item clearfix <?php if($interlorcuteur->_id == $activeObject->_id): ?>active-bordered<?php endif; ?>">
                <?php include_partial('compte/bloc', array('compte' => $interlorcuteur)); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="panel-body text-center">
        <span class="text-muted">Aucun interlocuteur</span>
    </div>
    <?php endif; ?>
    <?php if($modifiable): ?>
    <div class="panel-footer text-center">
        <a class="btn btn-xs btn-link" href="<?php echo url_for('compte_ajout', array('identifiant' => $societe->identifiant)); ?>"><span class="glyphicon glyphicon-plus-sign"></span> Créer un interlocuteur</a>
    </div>
    <?php endif; ?>
</div>
