<div style="display:none">
    <div id="add_produit_popup" class="add_produit_popup_certification_content">
        <h2>Choix d'un produit&nbsp;<a href="" class="msg_aide_drm  icon-msgaide" title="<?php echo getHelpMsgText('drm_ajout_produit_aide1'); ?>" style="float:right;"></a></h2>
        <br>
        <p>Merci de sélectionner un produit dans la liste ci-dessous :</p>
        <br>
        <form class="form-horizontal" action="<?php echo url_for('drm_choix_produit_add_produit', array('identifiant' => $drm->identifiant, 'periode_version' => $drm->getPeriodeAndVersion(), 'add_produit' => $form->getProduitFilter())) ?>" method="post">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <div class="row">
                <div class="col-sm-4 text-right">
                  <?php echo $form['produit']->renderLabel(); ?>
                </div>
                <div class="col-sm-8 text-left">
                  <?php echo $form['produit']->render(array('class' => 'autocomplete form-control')); ?>
                </div>
            </div>
              <br/>
              <div class="row">
                <div class="col-sm-4 text-right">
                  <?php echo $form['denomination_complementaire']->renderLabel(); ?>
                </div>
                <div class="col-sm-8 text-left">
                  <?php echo $form['denomination_complementaire']->render(array('class' => 'form-control')); ?>
                </div>
              </div>


            <br/>
            <div class="ligne_btn">
                <a id="popup_close_popup" class="btn_rouge btn_majeur annuler popup_close" style="float: left;" href="#" >Annuler</a>
                <button id="popup_confirm" type="submit" class="btn_validation" style="float: right;" ><span>Ajouter le produit</span></button>
            </div>
        </form>
    </div>
</div>
