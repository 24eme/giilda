<!-- #principal -->
<section id="principal">
	<p id="fil_ariane"><a href="<?php echo url_for('societe');?>">Page d'accueil</a>
            &gt; <a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>">
            <?php echo $societe->raison_sociale; ?></a> &gt; <strong><?php echo (!$compte->isNew())? $compte->nom_a_afficher : 'Nouvel interlocuteur'; ?></strong></p>

	<!-- #contacts -->
	<section id="contacts">
		<div id="nouveau_contact">
			<h2><?php echo (!$compte->isNew())? $compte->nom_a_afficher : 'Nouvel interlocuteur'; ?></h2>


			<form action="<?php echo ($compte->isNew())? url_for('compte_ajout', array('identifiant' => $societe->identifiant)) : url_for('compte_modification', array('identifiant' => $compte->identifiant)); ?>" method="post">
			<div class="form_btn">
				<a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>" class="btn_majeur btn_annuler">Annuler</a>
				<button class="btn_majeur btn_valider">Valider</button>
			</div>
				<div id="detail_contact" class="form_section ouvert">
					<h3>Détail de l'interlocuteur</h3>
					<?php include_partial('modificationDetail', array('compteForm' => $compteForm)); ?>
				</div>

				<div id="coordonnees_contact" class="form_section ouvert">
					<h3>Coordonnées de l'interlocuteur</h3>
					<div class="form_contenu">
						<?php include_partial('modification', array('compteForm' => $compteForm)); ?>
					</div>
				</div>
                            
			<div class="form_btn">
				<a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>" class="btn_majeur btn_annuler" >Annuler</a>
                                <button class="btn_majeur btn_valider">Valider</button>
			</div>
                            
			</form>

		</div>
	</section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Accueil des sociétés</span></a>
        </div>
    </div>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Accueil de la société</span></a>
        </div>
    </div>
    <?php if(!$compte->isNew()) : ?>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('compte_visualisation', array('identifiant' => $compte->identifiant)); ?>" class="btn_majeur btn_acces"><span>Retour à la visualisation</span></a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php
end_slot();
?> 
