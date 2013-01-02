<!-- #principal -->
<section id="principal">
	<p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">Contacts</a> &gt; <a href="#"><?php echo $societe->raison_sociale; ?></a> &gt; <strong><?php echo ($compte->nom)? $compte->nom : $compte->nom_a_afficher ;?></strong></p>

	<!-- #contacts -->
	<section id="contacts">
		<div id="nouveau_contact">
			<h2><?php echo ($compte->nom)? $compte->nom : $compte->nom_a_afficher ;?></h2>

			<div class="form_btn">
                            <a href="<?php echo url_for('compte_new',$compte);?>" class="btn_majeur btn_modifier">Modifier</a>
			</div>

				<div id="detail_contact" class="form_section ouvert">
					<h3>Détail du contact</h3>
					<?php include_partial('detailVisualisation', array('compte' => $compte)); ?>
				</div>

				<div id="coordonnees_contact" class="form_section ouvert">
					<h3>Coordonnées du contact</h3>
					<div class="form_contenu">
						<?php include_partial('coordonneesVisualisation', array('compte' => $compte)); ?>
					</div>
				</div>
			<div class="form_btn">
				<button class="btn_majeur btn_annuler">Annuler</button>
				<button class="btn_majeur btn_valider">Valider</button>
			</div>
		</div>
	</section>
</section>