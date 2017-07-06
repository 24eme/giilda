<form id="form_produit_declaration" method="post"
 action="<?php echo url_for('drm_edition_produit_ajout', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion(), 'details' => $detailsKey )) ?>" class="section_label_strong">
    <label>Saisir un produit</label>&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_mouvements_aide5'); ?>" style="padding: 0 0 0 10px;"></a>

   <br/>
    <?php echo $form['hashref']->render(); ?>
    <?php echo $form->renderHiddenFields(); ?>
   <br/>
   <br/>
   <span style="font-size: 9pt;"><?php echo getHelpMsgText('drm_mouvements_texte2'); ?></span>
</form>
