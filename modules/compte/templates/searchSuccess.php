<?php $args = array('q' => $q); ?>
<section id="principal" class="recherche_contact">
	
	<section id="contenu_etape">
		<form>
			<div id="recherche_contact" class="section_label_maj">
				<label for="champ_recherche">Recherche d'un contact&nbsp;:</label><br />
				<input id="champ_recherche" class="ui-autocomplete-input" type="text" name="q" value="<?php echo $q; ?>" role="textbox" aria-autocomplete="list" aria-haspopup="true"> 
				<button id="btn_rechercher" type="submit">Rechercher</button>
			</div>
		</form>
	</section>

	<span><?php echo $nb_results; ?> résultat(s) trouvé(s) (page <?php echo $current_page; ?> sur <?php echo $last_page; ?>)</span>
	
	<a class="btn_majeur btn_excel" href="<?php echo url_for('compte_search_csv', $args); ?>">Télécharger le tableur</a>

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
					<td><a href="<?php url_for('compte_visualisation', array('identifiant' => $data['identifiant'])); ?>">détail</a></td>
				</tr>

			<?php endforeach; ?>
		</tbody>
	</table>
	
	
	<?php $args = array('q' => $q); ?>
	<a href="<?php echo url_for('compte_search', $args); ?>"> <<- </a>
	<?php if ($current_page > 1) $args['page'] = $current_page - 1; ?>
	<a href="<?php echo url_for('compte_search', $args); ?>"> <- </a>
	<?php if ($current_page < $last_page) $args['page'] = $current_page + 1; else $args['page'] = $last_page ;?>
	<a href="<?php echo url_for('compte_search', $args); ?>"> -> </a>
	<?php $args['page'] = $last_page; ?>
	<a href="<?php echo url_for('compte_search', $args); ?>"> ->> </a>

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
