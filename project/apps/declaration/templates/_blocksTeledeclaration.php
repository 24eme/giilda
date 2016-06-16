<div class="col-xs-6">
    <div class="panel panel-default">
        <div class="panel-heading"><h4>Contrat</h4></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12" style="height: 150px;">

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">


                    <a class="btn btn-default" href="<?php echo url_for('vrac_societe',array('identifiant' => $etablissementPrincipal->identifiant)); ?>" >
                        <?php echo 'Accéder aux Contrat' ?>
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>
<?php if($teledeclaration_drm): ?>
<div class="col-xs-6">
    <div class="panel panel-default">
        <div class="panel-heading"><h4>DRM</h4></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12" style="height: 150px;">

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">

                    <a class="btn btn-default" href="<?php echo url_for('drm_societe',array('identifiant' => $etablissementPrincipal->identifiant)); ?>" >
                        <?php echo 'Accéder aux DRM' ?>
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>
