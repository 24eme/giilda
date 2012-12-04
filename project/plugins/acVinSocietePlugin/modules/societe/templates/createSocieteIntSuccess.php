<div id="contenu">

    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><a href="#">Page d'accueil</a> &gt; <a href="#">Contact</a> &gt; <strong>Création d'une société</strong></p>

        <!-- #contacts -->
        <section id="contacts">
		
			<div id="creation_societe">
				<h1>Création d'une nouvelle société</h1>
				
				<form action="" method="post">
				
					<div id="selection_societe" class="form_section">
						<h2>Sélectionner un type de société</h2>
						
						<div class="form_contenu">
							<div class="form_ligne">
								<label for="nom_societe">Nom de la société</label>
								<input type="text" id="nom_societe" />
							</div>
							<div class="form_ligne">
								<label for="type_societe">Type de société</label>
								<select id="type_societe">
									<option value="viticulteur">Viticulteur</option>
								</select>
							</div>
						</div>
					</div>
					
					<div id="detail_societe" class="form_section">
						
						<h2>Détail de la société</h2>
						
						<div class="form_contenu">
							<div class="form_ligne">
								<label for="detail_societe_nom">Nom de la société</label>
								<input type="text" id="detail_societe_nom" class="champ_long" />
							</div>
							<div class="form_ligne">
								<label for="detail_societe_abrege">Abrégé</label>
								<input type="text" id="detail_societe_abrege" />
								<label for="detail_societe_statut">Statut</label>
								<input type="radio" id="detail_societe_actif" />
								<label for="detail_societe_actif">Actif</label>
								<input type="radio" id="detail_societe_suspendu" />
								<label for="detail_societe_suspendu">Suspendu</label>
							</div>
							<div class="form_ligne">
								<label for="detail_societe_numero_compte">Numéros de Compte</label>
								<input type="checkbox" id="detail_societe_numero_compte" />
								<input type="checkbox" id="detail_societe_fournisseur" />
								<label for="detail_societe_fournisseur">Fournisseur</label>
							</div>
							<div class="form_ligne">
								<label for="detail_societe_siret">SIRET</label>
								<input type="text" id="detail_societe_siret" />
								<label for="detail_societe_code_naf">Code NAF</label>
								<input type="text" id="detail_societe_code_naf" />
							</div>
							<div class="form_ligne">
								<label for="detail_societe_tva_intracom">TVA Intracom</label>
								<input type="text" id="detail_societe_tva_intracom" />
							</div>
							<div class="form_ligne">
								<label for="detail_societe_enseigne">Enseigne</label>
								<input type="text" id="detail_societe_enseigne" />
							</div>
							<div class="form_ligne">
								<label for="detail_societe_commentaire">Commentaire</label>
								<textarea id="detail_societe_commentaire"></textarea>
							</div>
						</div>
					</div>
					
					<div id="coordonnees_societe" class="form_section">
						<h2>Coordonnées de la société</h2>
						
						<div class="form_contenu">
							<fieldset>
								<legend>Adresse</legend>
								
								<div class="form_ligne">
									<label for="coordonnees_societe_rue">N° et nom de rue</label>
									<input type="text" id="coordonnees_societe_rue" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_societe_adresse">Adresse complémentaire</label>
									<input type="text" id="coordonnees_societe_adresse" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_societe_cp">CP</label>
									<input type="text" id="coordonnees_societe_cp" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_societe_ville">Ville</label>
									<input type="text" id="coordonnees_societe_ville" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_societe_cedex">Cedex</label>
									<input type="text" id="coordonnees_societe_cedex" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_societe_pays">Pays</label>
									<select id="coordonnees_societe_pays">
										<option>France</option>
									</select>
								</div>
							</fieldset>
							<fieldset>
								<legend>E-mail / téléphone / fax</legend>
								
								<div class="form_ligne">
									<label for="coordonnees_societe_email">E-mail</label>
									<input type="text" id="coordonnees_societe_email" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_societe_telephone_bureau">Téléphone Bureau</label>
									<input type="text" id="coordonnees_societe_telephone_bureau" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_societe_mobile">Mobile</label>
									<input type="text" id="coordonnees_societe_mobile" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_societe_fax">Fax</label>
									<input type="text" id="coordonnees_societe_fax" />
								</div>
							</fieldset>
							<fieldset>
								<legend>Tags - étiquettes</legend>
								
								<div class="form_ligne">
									<label for="coordonnees_societe_tags">Tags</label>
									<input type="text" id="coordonnees_societe_tags" />
								</div>
							</fieldset>
						</div>
					</div>
					
					<div id="detail_etablissement" class="form_section">
						<h2>Détail de l'établissement</h2>
						
						<div class="form_contenu">
							<div class="form_ligne">
								<label for="detail_etablissement_type">Type d'établissement</label>
								<select id="detail_etablissement_type">
									<option>chai viticulteur</option>
								</select>
								<label for="detail_etablissement_ordre_affichage">Ordre affichage</label>
								<input type="text" id="detail_etablissement_ordre_affichage" />
							</div>							
							<div class="form_ligne">
								<label for="detail_etablissement_nom_chai">Nom du chai</label>
								<input type="text" id="detail_etablissement_nom_chai" class="champ_long" />
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_statut">Statut</label>
								<input type="radio" id="detail_etablissement_statut" />
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_cvi">CVI</label>
								<input type="text" id="detail_etablissement_cvi" />
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_raisins">Raisins et Moûts</label>
								<select id="detail_etablissement_raisins">
									<option>oui</option>
									<option>non</option>
								</select>
								<label for="detail_etablissement_exclusion_drm">Exclusion DRM</label>
								<select id="detail_etablissement_exclusion_drm">
									<option>oui</option>
									<option>oui</option>
								</select>
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_relance_ds">Relance DS</label>
								<select id="detail_etablissement_relance_ds">
									<option>oui</option>
									<option>non</option>
								</select>
								<label for="detail_etablissement_recette_locale">Recette locale</label>
								<select id="detail_etablissement_recette_locale">
									<option>oui</option>
									<option>oui</option>
								</select>
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_region_viticole">Région viticole</label>
								<select id="detail_etablissement_region_viticole">
									<option>oui</option>
									<option>non</option>
								</select>
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_type_dr">Type de DR</label>
								<select id="detail_etablissement_type_dr">
									<option>DRM</option>
									<option>DRA</option>
								</select>
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_type_liaison">Type de liaison (externe)</label>
								<select id="detail_etablissement_type_liaison">
									<option>Contrat interne</option>
								</select>
								<label for="detail_etablissement_societe">Societe</label>
								<input type="text" id="detail_etablissement_societe" />
								<a href="#">Ajouter une liaison</a>
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_site_fiche_publique">Site fiche publique</label>
								<input type="text" id="detail_etablissement_site_fiche_publique" />
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_carte_professionnelle">N° Carte professionnelle</label>
								<input type="text" id="detail_etablissement_carte_professionnelle" />
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_accises">N° d'ACCISES</label>
								<input type="text" id="detail_etablissement_accises" />
							</div>
							<div class="form_ligne">
								<label for="detail_etablissement_commentaire">Commentaire</label>
								<textarea id="detail_etablissement_commentaire"></textarea>
							</div>
							
							<fieldset>
								<legend>Adresse</legend>
								
								<div class="form_ligne">
									<label>Même adresse que la société ?</label>
									<input type="radio" id="detail_etablissement_oui" />
									<label for="detail_etablissement_oui">oui</label>
									<input type="radio" id="detail_etablissement_non" />
									<label for="detail_etablissement_non">non</label>
								</div>
								
								<div class="form_ligne">
									<label for="detail_etablissement_rue">N° et nom de rue</label>
									<input type="text" id="detail_etablissement_rue" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="detail_etablissement_adresse">Adresse complémentaire</label>
									<input type="text" id="detail_etablissement_adresse" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="detail_etablissement_cp">CP</label>
									<input type="text" id="detail_etablissement_cp" />
								</div>
								<div class="form_ligne">
									<label for="detail_etablissement_ville">Ville</label>
									<input type="text" id="detail_etablissement_ville" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="detail_etablissement_cedex">Cedex</label>
									<input type="text" id="detail_etablissement_cedex" />
								</div>
								<div class="form_ligne">
									<label for="detail_etablissement_pays">Pays</label>
									<select id="detail_etablissement_pays">
										<option>France</option>
									</select>
								</div>
							</fieldset>
							<fieldset>
								<legend>E-mail / téléphone / fax</legend>
								
								<div class="form_ligne">
									<label for="detail_etablissement_email">E-mail</label>
									<input type="text" id="detail_etablissement_email" />
								</div>
								<div class="form_ligne">
									<label for="detail_etablissement_telephone_bureau">Téléphone Bureau</label>
									<input type="text" id="detail_etablissement_telephone_bureau" />
								</div>
								<div class="form_ligne">
									<label for="detail_etablissement_mobile">Mobile</label>
									<input type="text" id="detail_etablissement_mobile" />
								</div>
								<div class="form_ligne">
									<label for="detail_etablissement_fax">Fax</label>
									<input type="text" id="detail_etablissement_fax" />
								</div>
							</fieldset>
						</div>
					</div>
					
					<div class="form_btn">
						<button type="submit" class="btn_majeur btn_valider">Créer</button>
					</div>
				</form>
			</div>
			
        </section>
        <!-- fin #contacts -->
    </section>
    <!-- fin #principal -->

    <!-- #colonne -->
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
    <!-- fin #colonne -->
</div>
