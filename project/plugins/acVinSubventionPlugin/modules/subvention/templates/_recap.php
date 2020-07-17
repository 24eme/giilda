<div class="row">
    <div class="col-xs-12">
        <h3><?php echo $subvention->declarant->raison_sociale ?> (SIRET n°<?php echo $subvention->declarant->siret ?>)</h3>
        <a href="<?php echo url_for("subvention_pdf", $subvention) ?>" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-file"></span>&nbsp;Vos informations</a>

        <h3>Dossier joint</h3>
        <a href="#" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-file"></span>&nbsp;XLS</a>

    </div>
</div>
