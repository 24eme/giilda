<ol class="breadcrumb">
    <li><a href="<?php echo url_for('vrac_upload_verification') ?>" class="active">Contrats</a></li>
</ol>

<h1>Rapport d'erreur sur l'import</h1>

<?php if (empty($verification)): ?>
    <div class="alert alert-success">
        Pas d'erreur détéctées. Vous pouvez y aller !
    </div>

    <form action="">
        <button></button>
    </form>
<?php else: ?>
    <div class="alert alert-danger" role="alert">
        <?php foreach ($verification as $contrat => $erreurs): ?>
        <p class="h4"><?= count($erreurs) ?> Erreurs sur le contrat : <?= $contrat ?></p>
            <ul>
                <?php foreach ($erreurs as $erreur): ?>
                    <li><?= $erreur ?></li>
                <?php endforeach ?>
            </ul>
        <?php endforeach ?>
    </div>

    <a href="<?= url_for('vrac') ?>" class="btn btn-primary center-block">Retour à l'upload</a>
<?php endif ?>
