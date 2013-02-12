<?php $args = array('q' => $q, 'contacts_all' => $contacts_all); ?>
<section id="principal" class="recherche_contact">
	
	<section id="contenu_etape">
		<form>
			<div id="recherche_contact" class="section_label_maj">
                           <div>
				<label for="champ_recherche">Recherche d'un contact&nbsp;:</label><br />
				<input id="champ_recherche" class="ui-autocomplete-input" type="text" name="q" value="<?php echo $q; ?>" role="textbox" aria-autocomplete="list" aria-haspopup="true"> 
				<button id="btn_rechercher" type="submit">Rechercher</button>
                           </div>
<div>
<label for="contacts_all">Inclure les contacts suspendus </label>
<input type="checkbox" value="1" name="contacts_all" id="contacts_all"<?php if($contacts_all) echo " CHECKED"; ?>/>
</div>
			</div>
		</form>
	</section>

	<span><?php echo $nb_results; ?> résultat(s) trouvé(s) (page <?php echo $current_page; ?> sur <?php echo $last_page; ?>)</span>
	
	<a class="btn_majeur btn_excel" href="<?php echo url_for('compte_search_csv', $args); ?>">Télécharger le tableur</a>
	
	<aside id="colonne_tag">
		<h2>tags sélectionnés</h2>
		<ul>
			<li>tag 1</li>
			<li>tag 2</li>
			<li>tag 3</li>
		</ul>
		
		<h2>tags dispos</h2>
		<ul>
			<li>tag 1</li>
			<li>tag 2</li>
			<li>tag 3</li>
		</ul>
		
		<h2>Créer un tag</h2>
		<label for="creer_tag">tag :</label>
		<input id="creer_tag" class="tags" type="text" />
	</aside>
	
	<?php if($nb_results > 0): ?>
	
		<table id="resultats_contact" class="table_recap">	
			<?php $cpt = 0; ?>

			<thead>
				<tr>
					<th>Nom</th>
					<th>Adresse</th>
					<th>Téléphone</th>
					<th>Email</th>
					<th>Détail</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach($results as $res): ?>

					<?php 
						$data = $res->getData();
						$class = ($cpt % 2) ? ' class="even"' : ''; 
					?>

					<tr <?php echo $class; ?>>
						<td><?php echo $data['nom_a_afficher']; ?></td>
						<td><?php echo $data['adresse']; ?>, <?php echo $data['code_postal']; ?>, <?php echo $data['commune']; ?></td>
						<td><?php echo $data['telephone_bureau']; ?> <?php echo $data['telephone_mobile'] ?> <?php echo $data['telephone_perso']; ?> <?php echo $data['fax']; ?></td>
						<td><?php echo $data['email']; ?></td>
						<td><a href="<?php echo url_for('compte_visualisation', array('identifiant' => $data['identifiant'])); ?>">détail</a></td>
					</tr>

				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="pagination">
			<div class="page_precedente">
				<?php $args = array('q' => $q); ?>
<?php if ($current_page > 1) : ?>
				<a href="<?php echo url_for('compte_search', $args); ?>"> <<- </a>
				<?php if ($current_page > 1) $args['page'] = $current_page - 1; ?>
				<a href="<?php echo url_for('compte_search', $args); ?>"> <- </a>
<?php endif; ?>
			</div>
			<div class="page_suivante">
				<?php if ($current_page < $last_page) $args['page'] = $current_page + 1; else $args['page'] = $last_page ;?>
<?php if ($current_page != $args['page']): ?>
				<a href="<?php echo url_for('compte_search', $args); ?>"> -> </a>
<?php endif; ?>
				<?php $args['page'] = $last_page; ?>
<?php if ($current_page != $args['page']): ?>
				<a href="<?php echo url_for('compte_search', $args); ?>"> ->> </a>
<?php endif; ?>
			</div>
		</div>
	
	<?php endif; ?>

</section>
<?php
	slot('colButtons'); 
?>
 <div class="bloc_col" >
	<h2>Actions</h2>

	<div class="contenu">
		<ul>
			<li class=""><a class="btn_majeur btn_acces" href="<?php echo url_for('societe');?>">Accueil des contacts</a></li>
		</ul>
	</div>
</div>
<?php
	end_slot();
?>
