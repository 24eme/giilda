<?php $certifKey = $certificationProduits->certification->getHashForKey(); ?>
<div style="display:none">
    <form action="<?php echo url_for('drm_choix_produit_add_produit', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion(), 'certification_hash' => $certificationProduits->certification->getHashForKey())) ?>" method="post">

        <div id="add_produit_<?php echo $certifKey; ?>" class="add_produit_popup_certification_content">
            <h2>Choisir un produit</h2>
           <?php echo $form['produits']->render(array('class' => 'autocomplete')); ?>
            <br/>
            <div class="ligne_btn">
                <a id="popup_close_<?php echo $certifKey; ?>" class="btn_rouge btn_majeur annuler popup_close" style="float: left;" href="#" >Annuler</a>           
                <button id="popup_confirm" type="submit" class="btn_validation" ><span>Signer le contrat</span></button>  
            </div>
        </div>
    </form>
</div>