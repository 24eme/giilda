<?php
$errors_exist = (count($errors) > 0);
?>
 <!-- #principal -->
    <section id="principal">        
            <?php include_partial('revendication/header', array('revendication' => $revendication,'actif' => $errors_exist)); ?>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Importer un fichier de volumes revendiqués (ODG)</h2>
            <?php if (count($revendication->_attachments)) :  ?>
            <div class="generation_facture_options" style="text-align: center; margin-bottom: 10px;">

                    <a class="btn_majeur btn_excel" href="<?php echo url_for('revendication_downloadCSV', $revendication); ?>">Télécharger le fichier originel</a>

            </div>
            <?php endif;?>
            <?php include_partial('revendication/formUpload', array('form' => $form, 'revendication' => $revendication)); ?>
            <?php 
            if($errors_exist) :
                include_partial('revendication/uploadErreurs',array('errors' => $errors,'md5' => $md5,'odg' => $revendication->odg, 'campagne' => $revendication->campagne));
            elseif(!$not_valid_file) : 
            ?>
            
            <div class="btn_etape">
                <a id="btn_upload_suivant" class="btn_etape_suiv" href="<?php echo url_for('revendication_view_erreurs', $revendication); ?>"><span>Suivant</span></a>
            </div>
            <?php
            endif;
            ?>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('revendication'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>