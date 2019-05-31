<section id="principal" class="drm">
    <h2>Vous venez de valider votre DRM</h2>

    <p>Vous avez la possibilité de recevoir vos factures par email automatiquement après chaque validation de DRM.</p>
    <p>Pour cela, il vous suffit de cocher la case ci-dessous.</p>
    <p><strong>NB: cette action est irreversible</strong></p>

    <form action="<?= url_for('drm_confirmation', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->periode)) ?>" method="post">
        <?= $form ?>
        <br/>
        <button type="submit" class="btn_majeur btn_vert">Valider mon choix</button>
    </form>
</section>
