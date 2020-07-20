<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
    <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $subvention->identifiant)) ?>"><?php echo $subvention->declarant->nom ?> (<?php echo $subvention->identifiant ?>)</a></li>
    <li class="active"><a href="">Demande de subvention <?php echo $subvention->operation ?></a></li>
</ol>

<section id="principal">
    <?php include_partial('subvention/etapes', array('subvention' => $subvention)); ?>

    <h2>Saisie du dossier</h2>

    <p>Cette consiste à décrire les opérations de communication à mener à l'aide d'un tableur : </p>

    <form class="form-horizontal" role="form" action="<?php echo url_for('subvention_dossier', array('identifiant' => $subvention->identifiant,'operation' => $subvention->operation)) ?>" method="post" enctype="multipart/form-data">
      <?php echo $form->renderGlobalErrors(); ?>
      <?php echo $form->renderHiddenFields(); ?>
    <div class="row">
        <div class="col-xs-9">
            <div>
            <h3>Étape 1</h3>
            <a class="btn btn-default btn-lg" style="width: 100%; text-align: left;" href="<?php echo url_for('subvention_xls', array('identifiant' => $subvention->identifiant,'operation' => $subvention->operation)) ?>"><span class="glyphicon glyphicon-file"></span> <?php if(!$subvention->hasXls()): ?>Télécharger le dossier vierge à compléter<?php else:?> Télécharger mon dossier déposé le <?php echo ($subvention->dossier_date)? (DateTime::createFromFormat("Y-m-d H:i:s",$subvention->dossier_date))->format("d/m/Y à H\hi") : ''; ?><?php endif; ?>
            </a>
            </div>
            <hr />
            <div>
                <h3>Étape 2</h3>
                <?php echo $form['file']->renderError() ?>
                <label class="btn btn-default btn-lg text-left" style="width: 100%; text-align: left;" for="subvention_dossier_file">
                    <?php echo $form['file']->render(array('style' => 'display:none', 'onchange' => "$('#upload-file-info').html(this.files[0].name)")) ?>
                    <span class="glyphicon glyphicon-download-alt"></span> <span id="upload-file-info"><?php if($subvention->hasXls()): ?>Remplacer le fichier complété<?php else: ?>Verser le fichier complété<?php endif ?></span>
                </label>
            </div>
        </div>
        <div class="col-xs-3">
            <?php include_partial('subvention/aide'); ?>
        </div>
    </div>
    <div style="margin-top: 30px;" class="row">
        <div class="col-xs-6">
            <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('subvention_infos', $subvention); ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Étape précédente</a>
        </div>
        <div class="col-xs-6 text-right">
            <button type="submit" class="btn btn-success">Étape suivante&nbsp;<span class="glyphicon glyphicon-chevron-right"></button>
        </div>
    </div>
    </form>
</section>
