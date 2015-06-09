<?php if($validation->hasErreurs()): ?>
<div class="alert alert-danger">
    <strong>Points bloquants</strong>
    <?php include_partial('document_validation/validationType', array('points' => $validation->getPoints('erreur'))) ?>
</div>
<?php endif; ?>

<?php if($validation->hasVigilances()): ?>
<div class="alert alert-warning">
    <strong>Points de vigilance</strong>
    <?php include_partial('document_validation/validationType', array('points' => $validation->getPoints('vigilance'))) ?>
</div>
<?php endif; ?>
