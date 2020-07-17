<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
    <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $subvention->identifiant)) ?>"><?php echo $subvention->declarant->nom ?> (<?php echo $subvention->identifiant ?>)</a></li>
    <li class="active"><a href="">Visualisation</a></li>
</ol>

<h1>Visualisation de la demande de subvention <?php echo $subvention->operation ?></h1>

<?php include_partial('subvention/recap', array('subvention' => $subvention)); ?>


<div class="row row-margin row-button" style="margin-top: 20px;">
    <div class="col-xs-6">
        <a href="" tabindex="-1" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Retour à mon espace subvention</a>
    </div>
    <div class="col-xs-6 text-right">
        <a href="<?php echo url_for('subvention_validationinterpro', $subvention) ?>" class="btn btn-success"><span class="glyphicon glyphicon glyphicon-check"></span>&nbsp;Statuer sur le dossier</a>
    </div>
</div>

<?php if(isset($formValidationInterpro)): ?>
<?php include_partial('subvention/modalValidationInterpro', array('form' => $formValidationInterpro, 'subvention' => $subvention)); ?>
<?php endif; ?>
