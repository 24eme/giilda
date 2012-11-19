<div id="contenu" class="revendication">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <form method="POST" action="<?php echo url_for('revendication_choose_row',array('odg' => $revendication->odg, 'campagne' => $revendication->campagne, 'num_ligne' => $form->getNumLigne())); ?>" >
                
                <h2>Il existe plusieurs lignes similaires dans le CSV</h2>
                <?php echo $form->renderGlobalErrors(); ?>
                <?php echo $form->renderHiddenFields(); ?>
				
				<div class="section_label_maj" id="recherche_operateur">
					<label><?php echo 'Veuillez choisir la ligne pertinente : '; ?></label>
					<?php echo $form['row_select']->render(); ?>
					
					<button class="btn_majeur btn_valider" type="submit">Valider</button>
				</div>
            </form>
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
