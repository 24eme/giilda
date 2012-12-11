<form method="POST" enctype="multipart/form-data" action="<?php echo url_for('revendication'); ?>" >
    <?php echo $form->renderGlobalErrors(); ?>
    <?php echo $form->renderHiddenFields(); ?>
    <div class="generation_facture_options">
        <ul>
            <li>
                <span>1. <?php echo $form['odg']->renderlabel(); ?></span>
                <?php echo $form['odg']->renderError() ?> 
                <?php echo $form['odg']->render(); ?>        
            </li>
            <li>
                <span>2. <?php echo $form['campagne']->renderlabel(); ?></span>
                <?php echo $form['campagne']->renderError() ?> 
                <?php echo $form['campagne']->render(); ?>
            </li>
        </ul>    
    <div class="btn_etape">
        <button type="submit" class="btn_majeur btn_valider">Créer</button>
    </div>			
    </div>
</form>