<div style="display: none;">
    <div id="add_crds_<?php echo $regime; ?>" class="add_crds_popup_content">
        <form action="<?php echo url_for('drm_ajout_crd', $form->getObject()); ?>" method="post">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <h2>Choisir un type de CRD</h2>
            <div class="ligne_form">
                <a href="#" data-reveal="reveal_crd_color" class="reveal-link">Afficher la liste des couleurs</a>
                <div style="display: none" id="reveal_crd_color">
                <span>
                    <?php echo $form['couleur_crd_'.$regime]->renderError(); ?>
                    <?php echo $form['couleur_crd_'.$regime]->renderLabel() ?>
                    <?php echo $form['couleur_crd_'.$regime]->render(array('class' => 'couleur_crd_choice')); ?>
                </span>
                &nbsp;<a href="" class="msg_aide_drm  icon-msgaide size-16" title="<?php echo getHelpMsgText('drm_crds_ajout_aide1'); ?>" style="float:right; padding: 0 10px 0 0;"></a>
                </div>
            </div>
            <div class="ligne_form">       
                <span>
                    <?php echo $form['litrage_crd_'.$regime]->renderError(); ?>
                    <?php echo $form['litrage_crd_'.$regime]->renderLabel() ?>
                    <?php echo $form['litrage_crd_'.$regime]->render(); ?>
                </span>
                &nbsp;<a href="" class="msg_aide_drm  icon-msgaide size-16" title="<?php echo getHelpMsgText('drm_crds_ajout_aide2'); ?>" style="float:right; padding: 0 10px 0 0;"></a>   
            </div>
            <div class="ligne_form">       
                <span>
                    <?php echo $form['stock_debut_'.$regime]->renderError(); ?>
                    <?php echo $form['stock_debut_'.$regime]->renderLabel() ?>
                    <?php echo $form['stock_debut_'.$regime]->render(); ?>
                </span>
                &nbsp;<a href="" class="msg_aide_drm  icon-msgaide size-16" title="<?php echo getHelpMsgText('drm_crds_ajout_aide3'); ?>" style="float:right; padding: 0 10px 0 0;"></a>
            </div>
            <div class="ligne_form">       
                <span>
                    <?php echo $form['genre_crd_'.$regime]->renderError(); ?>
                    <?php echo $form['genre_crd_'.$regime]->renderLabel() ?>
                    <?php echo $form['genre_crd_'.$regime]->render(); ?>
                </span>
            </div>
            <br/>
            <div class="ligne_btn">
                <a id="popup_close" class="btn_rouge btn_majeur annuler popup_close" style="float: left;" href="#" >Annuler</a>           
                <button id="popup_confirm" type="submit" class="btn_validation"style="float: right;" ><span>Ajouter une ligne CRD</span></button>  
            </div>
        </form>
    </div>
</div>
