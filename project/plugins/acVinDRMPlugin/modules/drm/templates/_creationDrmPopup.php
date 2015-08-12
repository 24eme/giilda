<?php use_helper('Date'); ?>
<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>
<div style="display: none;">
    <div id="drm_nouvelle_<?php echo $periode . '_' . $identifiant; ?>" class="popup_contenu popup_creation_drm">
        <h2>Création de la DRM <?php echo getFrPeriodeElision($periode); ?></h2>
        <br>
          <form action="<?php echo url_for('drm_choix_creation', array('identifiant' => $identifiant, 'periode' => $periode)); ?>" method="post" enctype="multipart/form-data">
            <?php echo $drmCreationForm->renderHiddenFields(); ?>
            <?php echo $drmCreationForm->renderGlobalErrors(); ?>
           
            <div class="ligne_form type_creation" id="type_creation_div_<?php echo $periode . '_' . $identifiant; ?>" >       
                <span>
                    <?php echo $drmCreationForm['type_creation']->renderError(); ?>
                    <?php echo $drmCreationForm['type_creation']->renderLabel() ?>    
                    <?php echo $drmCreationForm['type_creation']->render(); ?>
                </span>
            </div>
              <div style="display: none;" class="ligne_form" id="file_edi_div_<?php echo $periode . '_' . $identifiant; ?>">       
                <span>
                    <?php echo $drmCreationForm['file']->renderError(); ?>
                    <?php echo $drmCreationForm['file']->renderLabel() ?>    
                    <?php echo $drmCreationForm['file']->render(); ?>
                </span>
            </div>
            <br/>
            <div class="ligne_btn">
                <a id="drm_nouvelle_popup_close" class="btn_rouge btn_majeur annuler popup_close" style="float: left;" href="#" >Annuler</a>           
                <button id="drm_nouvelle_popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Commencer la DRM</span></button>  
            </div>
        </form>
    </div>
</div>