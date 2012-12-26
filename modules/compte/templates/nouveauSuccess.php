<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">Contacts</a> &gt; <a href="#"><?php echo $societe->raison_sociale; ?></a> &gt; <strong>Nouveau contact</strong></p>

        <!-- #contacts -->
        <section id="contacts">
            <div id="nouveau_contact">
                <h2>Nouveau contact</h2>
				
				<div class="form_btn">
					<button class="btn_majeur btn_annuler">Annuler</button>
					<button class="btn_majeur btn_valider">Valider</button>
				</div>
				
				<form action="<?php echo url_for('compte_new', array('identifiant' => $compte->identifiant)); ?>" method="post">
					<div id="detail_contact" class="form_section ouvert">
						<h3>Détail du contact</h3>
						<?php include_partial('modificationDetail', array('compteForm' => $compteForm)); ?>
					</div>
					
					<div id="coordonnees_contact" class="form_section ouvert">
						<h3>Coordonnées du contact</h3>
						<div class="form_contenu">
							<?php include_partial('modification', array('compteForm' => $compteForm)); ?>
						</div>
					</div>
				</form>
				
				<div class="form_btn">
					<button class="btn_majeur btn_annuler">Annuler</button>
					<button class="btn_majeur btn_valider">Valider</button>
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
