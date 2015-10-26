<?php use_helper('Date'); ?>
<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>
<div style="display: none;">
    <div id="drm_nouvelle_<?php echo $periode . '_' . $identifiant; ?>" class="popup_contenu popup_creation_drm">
        <h2>Création de la DRM <?php echo getFrPeriodeElision($periode); ?></h2>
        <br>
        <div style="font-weight: bold;"><?php echo getHelpMsgText('drm_creation_texte1'); ?></div>
        <br>
        <form action="<?php echo url_for('drm_choix_creation', array('identifiant' => $identifiant, 'periode' => $periode)); ?>" method="post" enctype="multipart/form-data">
            <?php echo $drmCreationForm->renderHiddenFields(); ?>
            <?php echo $drmCreationForm->renderGlobalErrors(); ?>

            <div class="ligne_form type_creation" id="type_creation_div_<?php echo $periode . '_' . $identifiant; ?>" >       
                <span>
                    <?php echo $drmCreationForm['type_creation']->renderError(); ?>
                    <ul class="radio_list">
                        <li style="width: 250px;">
                            <input type="radio" checked="checked" id="drmChoixCreation_type_creation_<?php echo DRMClient::DRM_CREATION_VIERGE; ?>" value="<?php echo DRMClient::DRM_CREATION_VIERGE; ?>" name="drmChoixCreation[type_creation]">&nbsp;<label for="drmChoixCreation_type_creation_<?php echo DRMClient::DRM_CREATION_VIERGE; ?>">Création d'une drm vierge</label>
                            &nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_creation_aide1'); ?>" style="float: right;"></a>
                        </li>

                        <li style="width: 250px;">
                            <input type="radio" id="drmChoixCreation_type_creation_<?php echo DRMClient::DRM_CREATION_NEANT; ?>" value="<?php echo DRMClient::DRM_CREATION_NEANT; ?>" name="drmChoixCreation[type_creation]">&nbsp;<label for="drmChoixCreation_type_creation_<?php echo DRMClient::DRM_CREATION_NEANT; ?>">Création d'une drm à néant</label>
                            &nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_creation_aide2'); ?>"style="float: right;"></a>
                        </li>
<!--                        <li style="width: 250px;">
                            <input type="radio" id="drmChoixCreation_type_creation_<?php // echo DRMClient::DRM_CREATION_EDI; ?>" value="CREATION_EDI" name="drmChoixCreation[type_creation]">&nbsp;<label for="drmChoixCreation_type_creation_<?php // echo DRMClient::DRM_CREATION_EDI; ?>">Création depuis un logiciel tiers</label>
                            &nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php // echo getHelpMsgText('drm_creation_aide3'); ?>"style="float: right;"></a>
                        </li>-->
                    </ul>

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