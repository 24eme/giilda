<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">Contact</a> &gt; <strong>Création d'une société</strong></p>

        <!-- #contacts -->
        <section id="contacts">
            <div id="creation_societe">
                <h2>Création d'une nouvelle société</h2>
				<div class="form_btn">
					<button type="submit" class="btn_majeur btn_annuler">Annuler</button>
					<button id="btn_valider" type="submit" class="btn_majeur btn_valider">Valider</button>
				</div>
                <form action="<?php echo url_for('societe_modification', array('identifiant' => $societeForm->getObject()->identifiant)); ?>" method="post">

                    <div id="detail_societe" class="form_section ouvert">
                        <h3>Détail de la société</h3>  
                        <?php include_partial('societeModification', array('societeForm' => $societeForm)); ?>
                    </div>
                    <div id="coordonnees_societe" class="form_section ouvert">
                        <h3>Coordonnées de la société</h3>
						<div class="form_contenu">
							<?php include_partial('compte/modification', array('compteForm' => $contactSocieteForm)); ?>
						</div>
                    </div>
                </form>
				<div class="form_btn">
					<button type="submit" class="btn_majeur btn_annuler">Annuler</button>
					<button id="btn_valider" type="submit" class="btn_majeur btn_valider">Valider</button>
				</div>
            </div>
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