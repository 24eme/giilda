<div id="contenu" class="revendication">
    
    <!-- #principal -->
    <section id="principal">        
            <?php include_partial('headerRevendication', array('revendication' => $revendication,'actif' => 0)); ?>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            
            <?php include_component('revendication', 'chooseEtablissement'); ?>
            
            <form method="POST" enctype="multipart/form-data" action="<?php echo url_for('revendication_upload'); ?>" >
                
                <h2>Importer un fichier de volumes revendiqués (ODG)</h2>
                <?php echo $form->renderGlobalErrors(); ?>
                <?php echo $form->renderHiddenFields(); ?>
                <div class="generation_facture_options">
                    <ul>
                        <li>
	                        <span>1. <?php  echo $form['odg']->renderlabel(); ?></span>
                            <?php echo $form['odg']->renderError() ?> 
                            <?php  echo $form['odg']->render(); ?>        
                        </li>

                        <li>
                        	<span>2. <?php  echo $form['campagne']->renderlabel(); ?></span>
                            <?php echo $form['campagne']->renderError() ?> 
                            <?php  echo $form['campagne']->render(); ?>
                        </li>
                        <li>
							<span>3. <?php  echo $form['file']->renderlabel(); ?></span>      
							<?php echo $form['file']->renderError(); ?>  
							<?php  echo $form['file']->render(); ?>
                        </li>
                    </ul>    
                </div>
				<div class="btn_etape">
					<button type="submit" class="btn_majeur btn_valider">Valider</button>
				</div>			
            </form>
            <?php 
            if(count($errors) > 0)
                include_partial('uploadErreurs',array('errors' => $errors,'md5' => $md5));
            ?>
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
