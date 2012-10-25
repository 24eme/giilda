<div id="contenu" class="revendication">
    
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <form method="POST" action="<?php echo url_for('revendication_add_alias_to_configuration',array('odg' => $revendication->odg, 'campagne' => $revendication->campagne, 'alias' => $form->getAlias())); ?>" >
                
                <h2>Choisir un produit correspondant à ce libelle</h2>
                <?php echo $form->renderGlobalErrors(); ?>
                <?php echo $form->renderHiddenFields(); ?>
                <div class="generation_facture_options">
                    <span>                        
                    <?php echo $form->getAlias(); ?> 
                    </span> 
                    <?php echo $form['produit_hash']->render(); ?>
                </div>
                <input type="submit" class="btn_majeur btn_valider" />
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
