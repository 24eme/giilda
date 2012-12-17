<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil > Contacts > </strong> Création d'une société</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Création d'une société</h2>
            <form action="<?php echo url_for('societe_creation'); ?>" method="post">
				<div id="recherche_societe" class="section_label_maj">
					<h2>Sélectionner un type de société </h2>
                                        <?php if($raison_sociale) : ?>
                                        <div class="error"> Attention, la société saisie correspond a une société existante!</div>  
                                          <?php endif; ?>
                                        <div class="section_label_maj <?php echo ($raison_sociale)? 'errors':''  ?>" id="recherche_societe">
							<?php 
                                                        echo $form['raison_sociale']->renderError(); 
                                                        echo $form['raison_sociale']->renderLabel(); 
                                                        echo $form['raison_sociale']->render(); 
                                                        ?>
						</div>
						<div class="section_label_maj" id="recherche_societe">
							<?php echo $form['type']->renderError(); ?>
							<?php echo $form['type']->renderLabel(); ?>
							<?php echo $form['type']->render(); ?>
						</div>
                                        <button id="btn_rechercher" type="submit" class="btn_majeur btn_acces">Créer</button>
				</div>
            <form>
        </section>
    </section>
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
</div>