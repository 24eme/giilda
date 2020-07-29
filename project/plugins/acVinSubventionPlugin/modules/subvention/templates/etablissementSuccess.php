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


    <h3>Contrat Relance Viti <?php if($subvention && $subvention->isApprouve()): ?>
        <small class="pull-right"><span class="label label-success">Approuvé par l'interprofession</span></small>
    <?php elseif($subvention && $subvention->isRefuse()): ?>
        <small class="pull-right"><span class="label label-danger">Réfusé par l'interprofession</span></small>
    <?php elseif($subvention && $subvention->isValide()): ?>
        <small class="pull-right"><span class="label label-default">En attente de validation par l'interprofession</span></small>
    <?php elseif($subvention): ?>
        <small class="pull-right"><span class="label label-warning">En cours de saisie</span></small>
    <?php else: ?>
        <small class="pull-right"><span class="label label-info">Non commencé</span></small>
    <?php endif; ?></h3>
    <hr style="margin-top: 0;"/>
                  <p>Texte explicatif</p>
    <hr />
                  <div class="row">
                        <div class="col-xs-4 col-xs-offset-4">
                        <?php if($subvention && $subvention->isValide()): ?>
                          <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-block btn-success">Visualiser la procédure</a>
                        <?php elseif($subvention): ?>
                          <a href="<?php echo url_for('subvention_infos',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-block btn-warning">Continuer la procédure</a>
                        <?php else: ?>
                          <a href="<?php echo url_for('subvention_creation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-block btn-default">Démarrer la procédure</a>
                        <?php endif; ?>
                        </div>
                    </div>





</section>
