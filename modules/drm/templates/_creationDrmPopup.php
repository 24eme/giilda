<?php use_helper('Date'); ?>
<?php use_helper('DRM'); ?>
<?php use_helper('Orthographe'); ?>
<div id="drm_nouvelle_<?php echo $periode . '_' . $identifiant; ?>" class="modal fade " role="dialog">
  <div class="modal-dialog">
    <form action="<?php echo url_for('drm_choix_creation', array('identifiant' => $identifiant, 'periode' => $periode)); ?>" method="post" enctype="multipart/form-data">
 <div class="modal-content">
      <div class="modal-header">
        <h2>Création de la DRM <?php echo getFrPeriodeElision($periode); ?></h2>
      </div>
       <div class="modal-body">
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
        </div>

       <div class="modal-footer">
                <a id="drm_nouvelle_popup_close" class="btn btn-danger pull-left popup_close" style="float: left;" href="#" >Annuler</a>
                <button id="drm_nouvelle_popup_confirm" type="submit" class="btn btn-success pull-right" style="float: right;" ><span>Commencer la DRM</span></button>
            </div>

          </form>
        </div>
</div>
</div>
