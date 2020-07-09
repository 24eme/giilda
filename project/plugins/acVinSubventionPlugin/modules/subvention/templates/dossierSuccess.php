<ol class="breadcrumb">
    <li class="active"><a href="<?php echo "#" ?>">Subvention</a></li>
</ol>
<h2 class="text-center">Saisie du dossier</h2>
<h3 class="text-center"><?php echo $subvention->declarant->raison_sociale ?> <small>(<?php echo $subvention->declarant->siret ?>)</small></h3>
<br/>
<div class="row">
    <div class="col-xs-10 col-xs-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading"><h2 class="panel-title">Téléchargement de fichier</h2></div>
            <div class="panel-body">
              <ul>
                <li><a href="#">Notice</a></li>
                  <li><a href="#">Charte Graphique</a></li>
              </ul>
            </div>
        </div>
    </div>
</div>
<br/>


<form class="form-horizontal" role="form" action="<?php echo url_for('subvention_dossier', array('identifiant' => $subvention->identifiant,'operation' => $subvention->operation)) ?>" method="post" enctype="multipart/form-data">
  <?php echo $form->renderGlobalErrors(); ?>
  <?php echo $form->renderHiddenFields(); ?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1">
      <h3 >Dossier de subvention <small>(en xls)</small></h3>
  </div>
</div>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
      <a class="btn btn-default btn-lg col-xs-10" href="#">Télécharger pour compléter</a>
  </div>
</div>
<br/>
<div class="row">


<div class="form-group">
  <div class="col-xs-8 col-xs-offset-4">
    <?php echo $form['file']->renderError() ?>
  </div>
</div>
    <div class="col-xs-8 col-xs-offset-2">
      <label class="btn btn-default btn-lg col-xs-6" for="subvention_dossier_file">
        <?php echo $form['file']->render(array('style' => 'display:none', 'onchange' => "$('#upload-file-info').html(this.files[0].name)")) ?>
          <span class='' id="upload-file-info">Choisir un fichier</span>
      </label>
      <button type="submit" class="btn btn-default btn-lg btn-upper col-xs-4">Déposer le dossier</button>


  </div>
</div>



    <div class="row">
        <div class="col-xs-6">
            <a class="btn btn-default" href="<?php url_for('subvention_infos',$subvention); ?>">Étape précédente</a>
        </div>
        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-success">Étape suivante</button>
        </div>
    </div>
</form>
