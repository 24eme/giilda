<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane">Page d'accueil > Contacts > <strong><?php echo $societe->raison_sociale; ?></strong></p>

        <!-- #contenu_etape -->
        <section id="contacts">
			
            <div id="visu_societe">
				<h2><?php echo $societe->raison_sociale; ?></h2>
				
				<div class="btn_haut">
					<a href="<?php echo url_for('societe_addContact', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur">Nouvel interlocuteur</a>
					&nbsp;
					<?php if($societe->canHaveChais()) : ?>  
						<a href="<?php echo url_for('societe_addEtablissement', array('identifiant' => $societe->identifiant)); ?>" class="btn_majeur">Nouvel Etablissement</a>
					<?php endif;?>
				</div>
				
				<div class="infos_societe">
					<p>
						Date de création : JJ/MM/AAAA <br />
						Dernière modification : JJ/MM/AAAA, par (user_name)
					</p>
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
         <?php
            include_component('societe', 'getInterlocuteurs', array('identifiant' => $societe->identifiant));
        ?>
    </aside>
</div>