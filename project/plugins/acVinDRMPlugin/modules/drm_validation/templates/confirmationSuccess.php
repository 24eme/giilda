<?php use_helper('Orthographe'); ?>
<?php use_helper('Date'); ?>
<?php use_helper('DRM'); ?>
<section id="principal" class="drm">
    <h2>Vous venez de valider votre DRM <?php echo getFrPeriodeElision($drm->periode); ?></h2>

    <p>Vous avez à présent la possibilité de recevoir vos factures par email automatiquement après chaque validation de DRM.</p>

    <p>Pour cela, il vous suffit de cocher la case ci-dessous.</p>
    <br/>
    <form action="<?= url_for('drm_confirmation', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->periode)) ?>" method="post">
        <?php echo $form; ?>
        <br/>
        <br/>
        <br/>
        <button type="submit" class="btn_majeur btn_vert">Valider mon choix</button>
    </form>
</section>
<?php
include_partial('drm/colonne_droite', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode));
?>
