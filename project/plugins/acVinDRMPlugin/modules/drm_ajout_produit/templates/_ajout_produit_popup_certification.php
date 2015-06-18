<div style="display:none">
    <div id="add_produit_<?php echo $certifKey; ?>" class="add_produit_popup_certification_content">
        <h2>Choix d'un produit</h2>
        <br>
        <form action="<?php echo url_for('drm_choix_produit_add_produit', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion(), 'certification_hash' => $certifKey)) ?>" method="post">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <?php echo $form['produit']->render(array('class' => 'autocomplete')); ?>
            <br/>
            <div class="ligne_btn">
                <a id="popup_close_<?php echo $certifKey; ?>" class="btn_rouge btn_majeur annuler popup_close" style="float: left;" href="#" >Annuler</a>           
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Ajouter le produit</span></button>  
            </div>
        </form>
    </div>
</div>