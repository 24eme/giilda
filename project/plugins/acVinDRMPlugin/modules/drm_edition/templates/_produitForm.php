<form id="form_produit_declaration" method="post" action="<?php echo url_for("drm_edition_produit_ajout", array('sf_subject' => $drm, 'details' => $detailsKey)) ?>">
    <div class="form-group">
    <?php echo $form['hashref']->render(array("placeholder" => "Ajouter un produit")); ?>
    <?php echo $form->renderHiddenFields(); ?>
    </div>
</form>
