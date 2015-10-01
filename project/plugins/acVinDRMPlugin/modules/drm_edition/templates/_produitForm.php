<form id="form_produit_declaration" method="post" action="<?php echo url_for("drm_edition_produit_ajout", $drm) ?>" class="section_label_strong">
    <label>Saisir un produit</label>&nbsp;<a href="" class="msg_aide_drm" title="<?php echo getHelpMsgText('drm_mouvements_aide5'); ?>"></a>
   
   <br/>
    <?php echo $form['hashref']->render(); ?>
    <?php echo $form->renderHiddenFields(); ?>
   <br/>
   <span><?php echo getHelpMsgText('drm_mouvements_texte2'); ?></span>
</form>