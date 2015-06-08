<div style="display: none;">
    <div id="add_crds_<?php echo $regime; ?>" class="add_crds_popup_content">
        <form action="<?php echo url_for('drm_ajout_crd', $drm); ?>" method="post">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
            <h2>Choisir un type de CRD</h2>
            <div class="ligne_form">       
                <span>
                    <?php echo $form['couleur_crd_'.$regime]->renderError(); ?>
                    <?php echo $form['couleur_crd_'.$regime]->renderLabel() ?>    
                    <?php echo $form['couleur_crd_'.$regime]->render(array('class' => 'autocomplete')); ?>
                </span>
            </div>
            <div class="ligne_form">       
                <span>
                    <?php echo $form['litrage_crd_'.$regime]->renderError(); ?>
                    <?php echo $form['litrage_crd_'.$regime]->renderLabel() ?>    
                    <?php echo $form['litrage_crd_'.$regime]->render(array('class' => 'autocomplete')); ?>
                </span>
            </div>
            <div class="ligne_form">       
                <span>
                    <?php echo $form['stock_debut_'.$regime]->renderError(); ?>
                    <?php echo $form['stock_debut_'.$regime]->renderLabel() ?>    
                    <?php echo $form['stock_debut_'.$regime]->render(); ?>
                </span>
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