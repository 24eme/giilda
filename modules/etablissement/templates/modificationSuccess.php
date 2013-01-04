
<!-- #principal -->
<section id="principal">
    <p id="fil_ariane">Page d'accueil > Contacts > <strong>Modification d'un établissement</strong></p>
	
        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Modification d'un établissement</h2>
			
			<div class="form_btn">
				<button type="submit" class="btn_majeur btn_annuler">Annuler</button>
				<button id="btn_valider" type="submit" class="btn_majeur btn_valider">Valider</button>
			</div>
			
            <form action="<?php echo url_for('etablissement_modification', array('identifiant' => $etablissementModificationForm->getObject()->identifiant)); ?>" method="post">
				<div id="detail_etablissement" class="form_section ouvert">
					<h3>Détail de l'établissement</h3>
					<?php include_partial('etablissement/modification', array('etablissementForm' => $etablissementModificationForm)); ?>
				</div>
				
				<div id="coordonnees_etablissement" class="form_section ouvert">
					<h3>Coordonnées de l'établissement</h3>
					<?php include_partial('compte/modification', array('compteForm' => $compteModificationForm, 'isSocieteCompte' => $isSocieteCompte)); ?>
				</div>
            </form>
			
			<div class="form_btn">
				<button type="submit" class="btn_majeur btn_annuler">Annuler</button>
				<button id="btn_valider" type="submit" class="btn_majeur btn_valider">Valider</button>
			</div>
        </section>
    </section>
</section>
    
