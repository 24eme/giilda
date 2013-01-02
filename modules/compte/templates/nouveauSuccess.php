<!-- #principal -->
<section id="principal">
	<p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">Contacts</a> &gt; <a href="#"><?php echo $societe->raison_sociale; ?></a> &gt; <strong>Nouveau contact</strong></p>

	<!-- #contacts -->
	<section id="contacts">
		<div id="nouveau_contact">
			<h2>Nouveau contact</h2>


			<form action="<?php echo url_for('compte_new', array('identifiant' => $compte->identifiant)); ?>" method="post">
			<div class="form_btn">
				<button class="btn_majeur btn_annuler">Annuler</button>
				<button class="btn_majeur btn_valider">Valider</button>
			</div>
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