<?php use_helper('Date'); ?>
<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>
<div style="display: none;">
    <div id="drm_nouvelle_<?php echo $periode . '_' . $identifiant; ?>" class="popup_contenu">
        <h2>Création de la DRM <?php echo getFrPeriodeElision($periode); ?></h2>
        <br>
        <form action="" method="post">
            
            <br/>
            <div class="ligne_btn">
                <a id="popup_close_" class="btn_rouge btn_majeur annuler popup_close" style="float: left;" href="#" >Annuler</a>           
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Commencer la DRM</span></button>  
            </div>
        </form>
    </div>
</div>