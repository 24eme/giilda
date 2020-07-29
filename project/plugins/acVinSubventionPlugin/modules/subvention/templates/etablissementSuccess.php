<ol class="breadcrumb">
  <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
  <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->siret ?>)</a></li>

</ol>

<section id="principal">
<?php if(!$isTeledeclarationMode): ?>
  <div class="row" id="formEtablissementChoice">
    <div class="col-xs-12">
      <?php include_component('subvention', 'formEtablissementChoice') ?>
    </div>
  </div>
<?php endif; ?>

    <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
            <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Contrat Relance Viti 

                    <?php if($subvention && $subvention->isApprouve()): ?>
                        <label class="label label-success pull-right">Approuvé par l'interprofession</label>
                    <?php elseif($subvention && $subvention->isRefuse()): ?>
                        <label class="label label-danger pull-right">Réfusé par l'interprofession</label>
                    <?php elseif($subvention && $subvention->isValide()): ?>
                        <label class="label label-default pull-right">En attente de validation par l'interprofession</label>
                    <?php elseif($subvention): ?>
                        <label class="label label-warning pull-right">En cours de saisie</label>
                    <?php else: ?>
                        <label class="label label-info pull-right">Non commencé</label>
                    <?php endif; ?></h3>
                  </div>
                  <div class="panel-body">
                      Texte explicatif
                  </div>
                  <div class="panel-footer">
                      <?php if($subvention && $subvention->isValide()): ?>
                          <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-block btn-success">Visualiser la procédure</a>
                      <?php elseif($subvention): ?>
                          <a href="<?php echo url_for('subvention_infos',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-block btn-warning">Continuer la procédure</a>
                      <?php else: ?>
                          <a href="<?php echo url_for('subvention_creation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-block btn-default">Démarrer la procédure</a>
                      <?php endif; ?>
                  </div>
            </div>
        </div>
    </div>




</section>
