
<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('societe');?>">Page d'accueil</a> &gt; Contacts &gt; <strong><?php echo $societe->raison_sociale; ?></strong></p>

        <!-- #contenu_etape -->
        <section id="contacts">
			
            <div id="visu_societe">
				<h2><?php echo $societe->raison_sociale; ?></h2>
				
				<div class="btn_haut">
					<a href="<?php echo url_for('compte_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur">Nouvel interlocuteur</a>
					&nbsp;
					<?php if($societe->canHaveChais()) : ?>  
						<a href="<?php echo url_for('etablissement_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur">Nouvel Etablissement</a>
					<?php endif;?>
				</div>
				
				<div class="infos_societe">
					<a href="<?php echo url_for('societe_modification', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_modifier">Modifier</a>
				</div>
				
				<?php include_partial('visualisationPanel', array('societe' => $societe)); ?>
				
				<?php if(count($etablissements)): ?>
				<?php endif; ?>
				<?php
					foreach ($etablissements as $etablissementId => $etb) :
						include_partial('etablissement/visualisation', array('etablissement' => $etb->etablissement, 'ordre' => $etb->ordre));
					endforeach;
				?>
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
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('compte_ajout',array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Nouvel interlocuteur</span></a>
        </div>
    </div>
    <?php if($societe->canHaveChais()) : ?>  
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('etablissement_ajout',array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Nouvel etablissement</span></a>
        </div>
    </div>
    <?php  endif; ?>
</div>
<?php
end_slot();
?>