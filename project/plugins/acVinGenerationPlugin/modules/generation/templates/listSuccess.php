<?php include_partial('facture/preTemplate'); ?>

<h3>Liste des générations</h3>

<?php if (!count($historyGeneration)): ?>
    <p>
        Aucune génération
    </p>
<?php else: include_partial('generation/list', array('generations' => $historyGeneration)); endif; ?>

<?php include_partial('facture/postTemplate'); ?>
