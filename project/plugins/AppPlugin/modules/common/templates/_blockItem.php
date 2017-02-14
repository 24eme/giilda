<div class="col-xs-4">
    <div class="panel panel-default">
        <div class="panel-heading"><h4><?php echo $libelle; ?></h4></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12" style="height: 150px;">
                    <?php echo $description; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <?php 
                    if($etablissement): ?>
                    <a class="btn btn-default" href="<?php echo url_for($route_etablissement,$etablissement); ?>" target="<?php echo $target; ?>">
                        <?php echo 'Accéder aux ' . $libelle ?>
                    </a>
                    <?php else: ?>
                    <a class="btn btn-default" href="<?php echo url_for($route); ?>" target="<?php echo $target; ?>">
                        <?php echo 'Accéder aux ' . $libelle ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>