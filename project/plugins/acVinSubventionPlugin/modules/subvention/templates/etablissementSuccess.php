<ol class="breadcrumb">
    <li class="active"><a href="<?php echo "#" ?>">Subvention</a></li>
</ol>
<div class="row row-margin">
    <div class="col-xs-8">
        <h2 class="vertical-center" style="margin: 0 0 20px 0;">Demande de subvention</h2>
    </div>
    <div class="col-xs-4 text-right">
        <a href="<?php echo url_for('subvention_creation',array('identifiant' => $etablissement->identifiant, 'operation' => 'COVID1')); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Demande de Subvention COVID1</a>
    </div>
</div>
