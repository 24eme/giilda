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
        <button id="btn_upload_charger" type="submit" class="btn_majeur btn_valider">Charger le fichier</button>
    </div>          
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        <?php if (count($revendication->_attachments)): ?>
        $('#btn_upload_charger').click(function() {
            return confirm("Les erreurs non traitées seront perdues.\n\nUn fichier a déjà été chargé, souhaitez vous le remplacez ?");
        });
        <?php endif; ?>

        $('#csvRevendication_file').change(function() {
            if($(this).val()) {
                $('#btn_upload_suivant').hide();
            } else {
                $('#btn_upload_suivant').show();
            }
        });
    });
</script>