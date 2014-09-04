    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="<?php echo url_for('societe');?>">Page d'accueil</a> &gt; <strong><?php echo $societe->raison_sociale; ?></strong></p>

        <!-- #contenu_etape -->
        <section id="contacts">
			
            <div id="visu_societe">
				<h2><?php echo $societe->raison_sociale; ?></h2>
				
				<div class="btn_haut">
                                  <?php if($modification || $reduct_rights) : ?>  
					<a href="<?php echo url_for('compte_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur">Nouvel interlocuteur</a>
					&nbsp;
					<?php if(!$reduct_rights && $societe->canHaveChais()) : ?>  
						<a href="<?php echo url_for('etablissement_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur">Nouvel Etablissement</a>
					<?php endif;?>
                                  <?php endif; ?>  
				</div>
				
				<?php include_partial('visualisationPanel', array('societe' => $societe, 'modification' => $modification, 'reduct_rights' => $reduct_rights)); ?>
                                <div id="detail_societe_coordonnees" class="form_section ouvert">
					<h3>Coordonnées de la société</h3>
					<div class="form_contenu">
                                            <?php include_partial('compte/coordonneesVisualisation', array('compte' => $societe->getMasterCompte())); ?>
					</div>
				</div>
                                
				<?php if(count($etablissements)): ?>
				<?php endif; ?>
				<?php
                                foreach ($etablissements as $etablissementId => $etb) :
                                    include_partial('etablissement/visualisation', array('etablissement' => $etb->etablissement, 'ordre' => $etb->ordre, 'fromSociete' => true));
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
        <?php if($modification) : ?>  
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('compte_ajout',array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Nouvel interlocuteur</span></a>
        </div>
		<?php if(!$reduct_rights && $societe->canHaveChais()) : ?>  
			<div class="btnRetourAccueil">
				<a href="<?php echo url_for('etablissement_ajout',array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur btn_acces"><span>Nouvel etablissement</span></a>
			</div>
		<?php  endif; ?>
        <?php  endif; ?>
    </div>
</div>
<?php
end_slot();
?>