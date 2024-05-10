<div class="row">
    <?php $etablissement = (isset($etablissement)) ? $etablissement : null ?>
    <?php if ($etablissement): ?>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <strong><?php echo $etablissement->nom; ?></strong>
                        </div>
                        <div class="text-center panel-body">
                            <?php echo $etablissement->famille; ?>&nbsp;
                            <br/>
                            <small class="text-muted"><?php echo $etablissement->identifiant; ?></small>
                            <br>
                            <small><?php echo $etablissement->siege->adresse . ' - ' . $etablissement->siege->commune . ' ' . $etablissement->siege->code_postal; ?></small>
                            <br>
                            <small class="text-muted">
                                <?php if ($etablissement->isViticulteur()): echo "CVI : " . $etablissement->cvi;
                                endif; ?>
                            </small>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php if($teledeclaration): ?>
<?php include_component('common', 'blocksTeledeclaration', array('etablissementPrincipal' => $etablissementPrincipal)); ?>
<?php include_component('common', 'blocksTeledeclarationExtra'); ?>
<?php elseif($etablissement): ?>
<?php include_component('common', 'blocks', array('etablissement' => $etablissement)); ?>
<?php else: ?>
<div style="margin: 40px;">
<h1>Bienvenue dans l'espace interprofessionnel</h1>
<p>Le menu en entête de cette page vous permet d'accéder aux services mis à disposition pour vous par l'interprofession.</p>
</div>
<?php endif; ?>

</div>
