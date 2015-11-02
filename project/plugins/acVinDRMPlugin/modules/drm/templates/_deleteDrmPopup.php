<?php use_helper('Date'); ?>
<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>
<div style="display: none;">
    <div id="drm_delete_popup" class="popup_contenu drm_delete_popup_content">
        <h2>Suppression de la DRM <?php echo getFrPeriodeElision($drm->periode); ?></h2>
        <br>
        <form action="<?php echo url_for('drm_delete', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion())); ?>" method="post" >
            <?php echo $deleteForm->renderHiddenFields(); ?>
            <?php echo $deleteForm->renderGlobalErrors(); ?>

               <p>Etes-vous sûr(e) de vouloir cette DRM <?php echo getFrPeriodeElision($drm->periode); ?> ?</p>               
        
            <div class="ligne_btn">
                <a id="drm_delete_popup_close" class="btn_rouge btn_majeur annuler popup_close" style="float: left;" href="#" >Annuler</a>           
                <button id="drm_delete_popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Suprimer la DRM</span></button>  
            </div>
        </form>
    </div>
</div>