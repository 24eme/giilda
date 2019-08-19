<!-- #principal -->
<section id="principal">
	<p id="fil_ariane"><a href="<?php echo url_for('societe');?>">Page d'accueil</a>
            &gt; <a href="<?php echo url_for('societe_visualisation',array('identifiant'=> $societe->identifiant));?>">
            <?php echo $societe->raison_sociale; ?></a> &gt;
            <strong><?php echo ($compte->nom_a_afficher)? $compte->nom_a_afficher : $compte->nom ;?></strong></p>

	<!-- #contacts -->
	<section id="contacts">
		<div id="nouveau_contact">
                    <h2><?php echo ($compte->nom_a_afficher)? $compte->nom_a_afficher : $compte->nom ;?>&nbsp;(<?php echo $compte->identifiant;?>)</h2>

			<div class="form_btn">
                            <?php if($modification || $reduct_rights): ?>
                            <a href="<?php echo url_for('compte_modification',$compte);?>" class="btn_majeur btn_modifier">Modifier</a>
                            <a href="<?php echo url_for('compte_search', array('q' => $compte->identifiant)); ?>" class="btn_majeur btn_nouveau" style="float: right;">Ajouter un tag</a>
                            <?php endif; ?>
                        </div>

				<div id="detail_contact" class="form_section contact ouvert">
					<h3>Détail de l'interlocuteur</h3>
					<?php include_partial('detailVisualisation', array('compte' => $compte)); ?>
				</div>

				<div id="coordonnees_contact" class="form_section contact ouvert">
					<h3>Coordonnées de l'interlocuteur</h3>
					<div class="form_contenu">
						<?php include_partial('coordonneesVisualisation', array('compte' => $compte)); ?>
					</div>
				</div>
 			  <?php if ($compte->exist("mot_de_passe") && $compte->mot_de_passe && $compte->getSociete()->getMasterCompte()->hasDroit(Roles::TELEDECLARATION)): ?>
				  <div id="coordonnees_contact" class="form_section ouvert">
				    <h3>Télédeclaration</h3>
				    <div class="form_contenu">
				      <fieldset>
				        <label for="teledeclaration_login" class="label_liste">
				          Login :
				        </label>
				        <?php echo $compte->identifiant; ?>
				      </fieldset>
				      <fieldset>
				            <label for="teledeclaration_email" class="label_liste">
				              Email :
				            </label>
				            <?php echo $compte->getEmail(); ?>
				      </fieldset>
				      <?php if ($compte->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_NOUVEAU) : ?>
				        <fieldset>
				          <label for="teledeclaration_mot_de_passe" class="label_liste">
				            Code de création :
				          </label>
				          <?php echo str_replace('{TEXT}', '', $compte->mot_de_passe); ?>
				        </fieldset>
				    <?php elseif(preg_match('/\{OUBLIE\}/', $compte->mot_de_passe)): ?>
				          <fieldset>
				            <label for="teledeclaration_email" class="label_liste">
				              Code de création :
				            </label>
				            <?php $lien = 'https://'.sfConfig::get('app_routing_context_production_host').url_for("compte_teledeclarant_mot_de_passe_oublie_login", array("login" => $compte->identifiant, "mdp" => str_replace("{OUBLIE}", "", $compte->mot_de_passe))); ?>
				            En procédure de mot de passe oublié
				          </fieldset>
				          <pre>Lien de réinitialisation de mot de passe reçu dans le mail :
				          <?php echo $lien; ?></pre>
				      <?php else: ?>
				        <fieldset>
				          <label for="teledeclaration_email" class="label_liste">
				            Code de création :
				          </label>
				          Compte déjà crée
				        </fieldset>
				      <?php endif; ?>
				    </div>
				  </div>
				<?php endif; ?>

				<?php if(count($compte->getDroits()) && (!$compte->exist("mot_de_passe") || !$compte->mot_de_passe)): ?>
					<div class="form_btn">
							<?php if($modification || $reduct_rights): ?>
										<a onclick='return confirm("Êtes vous sûr de vouloir assigner un code de création à ce compte?")' href="<?php echo url_for('compte_nouveau_code_creation', array('identifiant' => $compte->identifiant)); ?>" class="btn_majeur" style="float: right;">Ajouter un code de création</a>
							<?php endif; ?>
				 </div>
				<?php endif; ?>
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
		<?php if(!$reduct_rights): ?>
		<div class="ligne_btn txt_centre">
				<div class="btnConnexion">
						<a href="<?php echo url_for('compte_teledeclarant_debrayage', array('identifiant' => $compte->identifiant)); ?>" class="btn_majeur lien_connexion"><span>Connexion à la télédecl.</span></a>
				</div>
		</div>
	<?php endif; ?>
</div>
<?php
end_slot();
?>
