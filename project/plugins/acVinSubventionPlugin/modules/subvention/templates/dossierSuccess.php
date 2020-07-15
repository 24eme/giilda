<ol class="breadcrumb">
    <li class="active"><a href="<?php echo "#" ?>">Subvention</a></li>
</ol>

<section id="principal">
    <?php include_partial('subvention/etapes', array('subvention' => $subvention)); ?>

    <h2 class="">Saisie du dossier</h2>
    <h3 class=""><?php echo $subvention->declarant->raison_sociale ?> <small>(<?php echo $subvention->declarant->siret ?>)</small></h3>
    <br/>

    <form class="form-horizontal" role="form" action="<?php echo url_for('subvention_dossier', array('identifiant' => $subvention->identifiant,'operation' => $subvention->operation)) ?>" method="post" enctype="multipart/form-data">
      <?php echo $form->renderGlobalErrors(); ?>
      <?php echo $form->renderHiddenFields(); ?>
    <div class="row">
<div class="col-xs-9">

<div class="row">
    <div class="col-xs-10">
      <h3 >Dossier de subvention <small>(en xls)</small></h3>
  </div>
</div>
<div class="row">
    <div class="col-xs-12">
      <a class="btn btn-default btn-lg col-xs-12" style="text-align:left; padding-left:16px;" href="<?php echo url_for('subvention_xls', array('identifiant' => $subvention->identifiant,'operation' => $subvention->operation)) ?>">
        <span class="glyphicon glyphicon-file" style=" padding-right:14px;"></span> <?php if(!$subvention->hasXls()): ?>Télécharger pour compléter<?php else:?> Télécharger mon dossier déposé le <?php echo ($subvention->dossier_date)? (DateTime::createFromFormat("Y-m-d H:i:s",$subvention->dossier_date))->format("d/m/Y à H\hi") : ''; ?><?php endif; ?>
        </a>
  </div>

</div>

<br/>
<div class="row" <?php if($subvention->hasXls()): ?>id="subvention-upload"<?php endif ?> >
  <div class="col-xs-12">
    <?php echo $form['file']->renderError() ?>
  </div>
    <div class="col-xs-12">
      <label class="btn btn-default btn-lg col-xs-12" for="subvention_dossier_file">
        <?php echo $form['file']->render(array('style' => 'display:none', 'onchange' => "$('#upload-file-info').html(this.files[0].name)")) ?>
          <span class="glyphicon glyphicon-download-alt pull-left">&nbsp;</span> <span class="pull-left" id="upload-file-info"><?php if($subvention->hasXls()): ?>Remplacer le fichier<?php else: ?>Choisir un fichier<?php endif ?></span>
      </label>
    </div>
</div>
<br/>
</div>
<div class="col-xs-3">
    <br/>
    <br/>
  <div class="row">
      <div class="col-xs-12">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h2 class="panel-title">Aide</h2>
              </div>
              <div class="panel-body">
                <ul>
                  <li><a href="#">Notice</a></li>
                    <li><a href="#">Charte Graphique</a></li>
                </ul>
              </div>
          </div>
      </div>
  </div>

</div>
</div>
<div class="row">
    <div class="col-xs-6">
        <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('subvention_infos', $subvention); ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Étape précédente</a>
    </div>
    <div class="col-xs-6 text-right">
        <button type="submit" class="btn btn-success">Étape suivante&nbsp;<span class="glyphicon glyphicon-chevron-right"></button>
    </div>
</div>
</form>
</section>
