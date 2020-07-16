<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
    <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $subvention->identifiant)) ?>"><?php echo $subvention->declarant->nom ?> (<?php echo $subvention->identifiant ?>)</a></li>
    <li class="active"><a href="">Visualisation</a></li>
</ol>

<?php if(isset($formValidationInterpro)): ?>
<?php include_partial('subvention/modalValidationInterpro', array('form' => $formValidationInterpro)); ?>
<?php endif; ?>
