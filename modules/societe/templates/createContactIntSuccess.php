<div id="contenu">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane">Page d'accueil > Contacts > EARL Yannick &amp; Nicole Amirault > <strong>Nouveau contact</strong></p>

        <!-- #contacts -->
        <section id="contacts">
			
			<div id="nouveau_contact">
			
				<h2>Nouveau contact</h2>
				<h3>EARL Yannick &amp; Nicole Amirault</h3>

				<div class="form_btn">
					<button type="submit" class="btn_majeur btn_annuler">Annuler</button>
					<button type="submit" class="btn_majeur btn_valider">Valider</button>
				</div>

				<form action="" method="post">
					<div id="detail_contact" class="form_section">
						<h3>Détail du contact</h3>

						<div class="form_contenu">

							<div class="form_ligne">
								<div class="form_colonne">
									<label for="detail_contact_civilite">Civilité</label>
									<select id="detail_contact_civilite">
										<option>Mme</option>
										<option>Mlle</option>
										<option>M.</option>
										<option>Société</option>
									</select>
								</div>
								<div class="form_colonne">
									<label for="detail_contact_ordre_affichage">Ordre affichage</label>
									<select id="detail_contact_ordre_affichage">
										<option>1</option>
										<option>2</option>
									</select>
								</div>
							</div>

							<div class="form_ligne">
								<label for="detail_contact_prenom">Prénom</label>
								<input type="text" id="detail_contact_prenom" class="champ_long" />
							</div>

							<div class="form_ligne">
								<label for="detail_contact_nom">Nom</label>
								<input type="text" id="detail_contact_nom" class="champ_long" />
							</div>

							<div class="form_ligne">
								<label for="detail_contact_fonction">Fonction</label>
								<input type="text" id="detail_contact_fonction" />
							</div>

							<div class="form_ligne">
								<label for="detail_contact_commentaire">Commentaire</label>
								<textarea id="detail_contact_commentaire"></textarea>
							</div>

						</div>
					</div>

					<div id="coordonnees_contact" class="form_section">

						<h3>Coordonnées du contact</h3>

						<div class="form_contenu">
							<fieldset>
								<legend>Adresse</legend>

								<div class="form_ligne">
									<label class="meme_adresse">Même adresse que la société ?</label>
									<input type="radio" id="coordonnees_contact_oui" />
									<label for="coordonnees_contact_oui" class="label_court">oui</label>
									<input type="radio" id="coordonnees_contact_non" />
									<label for="coordonnees_contact_non" class="label_court">non</label>
								</div>

								<div class="form_ligne">
									<label for="coordonnees_contact_rue">N° et nom de rue</label>
									<input type="text" id="coordonnees_contact_rue" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_contact_adresse">Adresse complémentaire</label>
									<input type="text" id="coordonnees_contact_adresse" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_contact_cp">CP</label>
									<input type="text" id="coordonnees_contact_cp" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_contact_ville">Ville</label>
									<input type="text" id="coordonnees_contact_ville" class="champ_long" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_contact_cedex">Cedex</label>
									<input type="text" id="coordonnees_contact_cedex" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_contact_pays">Pays</label>
									<select id="coordonnees_contact_pays">
										<option>France</option>
									</select>
								</div>
							</fieldset>
							<fieldset>
								<legend>E-mail / téléphone / fax</legend>

								<div class="form_ligne">
									<label for="coordonnees_contact_email">E-mail</label>
									<input type="text" id="coordonnees_contact_email" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_contact_telephone_bureau">Téléphone Bureau</label>
									<input type="text" id="coordonnees_contact_telephone_bureau" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_contact_mobile">Mobile</label>
									<input type="text" id="coordonnees_contact_mobile" />
								</div>
								<div class="form_ligne">
									<label for="coordonnees_contact_fax">Fax</label>
									<input type="text" id="coordonnees_contact_fax" />
								</div>
							</fieldset>
							<fieldset>
								<legend>Tags - étiquettes</legend>

								<div class="form_ligne">
									<label for="coordonnees_contact_tags">Tags</label>
									<input type="text" id="coordonnees_contact_tags" />
								</div>
							</fieldset>
						</div>
					</div>
				</form>
			
				<div class="form_btn">
					<button type="submit" class="btn_majeur btn_annuler">Annuler</button>
					<button type="submit" class="btn_majeur btn_valider">Valider</button>
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