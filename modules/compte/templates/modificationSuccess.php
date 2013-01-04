<!-- #principal -->
<section id="principal">
	<p id="fil_ariane"><a href="<?php echo url_for('societe');?>">Page d'accueil</a> &gt; Contacts
            &gt; <a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>">
            <?php echo $societe->raison_sociale; ?></a> &gt; <strong><?php echo (!$compte->isNew())? $compte->nom_a_afficher : 'Nouveau contact'; ?></strong></p>

	<!-- #contacts -->
	<section id="contacts">
		<div id="nouveau_contact">
			<h2><?php echo (!$compte->isNew())? $compte->nom_a_afficher : 'Nouveau contact'; ?></h2>


			<form action="<?php echo ($compte->isNew())? url_for('compte_ajout', array('identifiant' => $societe->identifiant)) : url_for('compte_modification', array('identifiant' => $compte->identifiant)); ?>" method="post">
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