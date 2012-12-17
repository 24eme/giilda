<?php
$errors_exist = (count($errors) > 0);
?>
<div id="contenu" class="revendication">
    
    <!-- #principal -->
    <section id="principal">        
            <?php include_partial('revendication/header', array('revendication' => $revendication,'actif' => $errors_exist)); ?>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Importer un fichier de volumes revendiqués (ODG)</h2>
            <?php include_partial('revendication/formUpload', array('form' => $form)); ?>
            
            <?php 
            if($errors_exist)
                include_partial('revendication/uploadErreurs',array('errors' => $errors,'md5' => $md5,'odg' => $revendication->odg));
            ?>

            <div class="btn_etape">
                <a class="btn_etape_suiv" href="<?php echo url_for('revendication_view_erreurs', $revendication); ?>"><span>Suivant</span></a>
            </div>
        </section>
        <!-- fin #contenu_etape -->
    </section>
    <!-- fin #principal -->
    
    <!-- #colonne -->
    <aside id="colonne">
        <div class="bloc_col" id="contrat_aide">
            <h2>Aide</h2>
            
            <div class="contenu">
                <ul>
                    <li class="raccourcis"><a href="#">Raccourcis clavier</a></li>
                    <li class="assistance"><a href="#">Assistance</a></li>
                    <li class="contact"><a href="#">Contacter le support</a></li>
                </ul>
            </div>
        </div>
    </aside>
    <!-- fin #colonne -->
</div>
