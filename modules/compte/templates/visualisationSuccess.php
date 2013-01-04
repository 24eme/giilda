<!-- #principal -->
<section id="principal">
	<p id="fil_ariane"><a href="<?php echo url_for('societe');?>">Page d'accueil</a> &gt; Contacts
            &gt; <a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>">
            <?php echo $societe->raison_sociale; ?></a> &gt; 
            <strong><?php echo ($compte->nom_a_afficher)? $compte->nom_a_afficher : $compte->nom ;?></strong></p>

	<!-- #contacts -->
	<section id="contacts">
		<div id="nouveau_contact">
                    <h2><?php echo ($compte->nom_a_afficher)? $compte->nom_a_afficher : $compte->nom ;?>&nbsp;(<?php echo $compte->identifiant;?>)</h2>

			<div class="form_btn">
                            <a href="<?php echo url_for('compte_modification',$compte);?>" class="btn_majeur btn_modifier">Modifier</a>
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