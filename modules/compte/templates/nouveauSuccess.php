<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">Contacts</a> &gt; <a href="#"><?php echo $societe->raison_sociale; ?></a> &gt; <strong>Nouveau contact</strong></p>

        <!-- #contacts -->
        <section id="contacts">
            <div id="nouveau_contact">
                <h2>Nouveau contact</h2>
				
				<div class="form_btn">
					<button class="btn_majeur btn_annuler">Annuler</button>
					<button class="btn_majeur btn_valider">Valider</button>
				</div>
				
				<form action="<?php echo url_for('compte_new', array('identifiant' => $compte->identifiant)); ?>" method="post">
					<?php 
						include_partial('modificationDetail', array('compteForm' => $compteForm)); 
						include_partial('modification', array('compteForm' => $compteForm)); 
					?>
				</form>
				
				<div class="form_btn">
					<button class="btn_majeur btn_annuler">Annuler</button>
					<button class="btn_majeur btn_valider">Valider</button>
				</div>
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
    </aside>
</div>
