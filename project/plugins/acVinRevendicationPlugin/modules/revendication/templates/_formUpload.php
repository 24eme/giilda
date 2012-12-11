<form method="POST" enctype="multipart/form-data" action="<?php echo url_for('revendication_upload', $form->getDocument()); ?>" >
    <?php echo $form->renderGlobalErrors(); ?>
    <?php echo $form->renderHiddenFields(); ?>
    <div class="generation_facture_options">
        <ul>
            <li>
                <span><?php echo $form['file']->renderlabel(); ?></span>      
                <?php echo $form['file']->renderError(); ?>  
                <?php echo $form['file']->render(); ?>
            </li>
        </ul>    
    <div class="btn_etape">
        <button type="submit" class="btn_majeur btn_valider">Charger le fichier</button>
    </div>          
    </div>
</form>