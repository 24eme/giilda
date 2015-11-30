<script>
   $(document).ready(function() {
   $(".removetag").click(function() {
       return confirm('Etes vous sur(e) de vouloir supprimer définivement ce tag pour ces <?php echo $nb_results; ?> fiches ?');
     });
     });
</script>
	<section class="col-xs-9" id="contenu_etape">
		<form>
			<div id="recherche_contact" class="section_label_maj">
				
                <div class="input-group">
                    <input id="champ_recherche" class="form-control input-lg" type="text" name="q" value="<?php echo $q; ?>"> 
                    <span class="input-group-btn">
                        <button class="btn btn-lg btn-info" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>

<div>
<label for="contacts_all">Inclure les contacts suspendus </label>
<input type="checkbox" value="1" name="contacts_all" id="contacts_all"<?php if($contacts_all) echo " CHECKED"; ?>/>
</div>
			</div>
		</form>

	<span> (page <?php echo $current_page; ?> sur <?php echo $last_page; ?>)</span>
        
	
	
	
	<?php if($nb_results > 0): ?>
		
        		<div class="pagination">
			<div class="page_precedente">
				<?php 
                                $args_copy = $args;
                                $args = array('q' => $q, 'tags' => $args['tags']); ?>
<?php if ($current_page > 1) : ?>
				<a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_precedente"> << </a>
				<?php if ($current_page > 1) $args['page'] = $current_page - 1; ?>
				<a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_precedente"> < </a>
<?php endif; ?>
			</div>
			<div class="page_suivante">
				<?php if ($current_page < $last_page) $args['page'] = $current_page + 1; else $args['page'] = $last_page ;?>
<?php if ($current_page != $args['page']): ?>
				<a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_precedente"> > </a>
<?php endif; ?>
				<?php $args['page'] = $last_page; ?>
<?php if ($current_page != $args['page']): ?>
                                <a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_suivante"> >> </a>
<?php endif; ?>
			</div>
		</div>
        
		
		
			<?php $cpt = 0; ?>

            <div class="list-group">
			<?php foreach($results as $res): ?>

				<?php 
					$data = $res->getData();
                    $societe_informations = $data['societe_informations'];
				?>
                        
				<div class="list-group-item">
                    <h4 class="list-group-item-heading">
                        <span class="glyphicon glyphicon-home"></span> 
                        <a href="<?php echo url_for('compte_visualisation', array('identifiant' => $data['identifiant'])); ?>"><?php echo $data['nom_a_afficher']; ?></a> 
                        <?php if($societe_informations['raison_sociale'] && $societe_informations['raison_sociale'] != $data['nom_a_afficher']): ?><small><span class="glyphicon glyphicon-home"></span> <?php echo $societe_informations['raison_sociale'] ?></small>
                        <?php endif; ?>
                        <small class="pull-right"><span class="label label-info"><?php echo $societe_informations['type'] ?></span></small>
                    </h4>
                    <p class="list-group-item-text">
                        <?php echo $data['adresse']; ?>, <?php echo $data['code_postal']; ?> <?php echo $data['commune']; ?>
                    </p>
                    <ul class="list-inline" style="margin-bottom: 0;">
                            <?php if($data['email']): ?>
                            <li><a href="mailto:<?php echo $data['email']; ?>"><?php echo $data['email']; ?></a></li>
                            <?php endif; ?>
                            <?php if($data['telephone_bureau']): ?>
                            <li>Bureau : <?php echo $data['telephone_bureau'] ?></li>
                            <?php endif; ?>
                            <?php if($data['telephone_mobile']): ?>
                            <li>Mobile : <?php echo $data['telephone_mobile'] ?></li>
                            <?php endif; ?>
                            <?php if($data['telephone_perso']): ?>
                            <li>Perso : <?php echo $data['telephone_perso'] ?></li>
                            <?php endif; ?>
                        </ul>
                         
                     <!--
                                            <?php 
                                            $class_picto = "contact_picto";
                                            $compte_type = $data['compte_type'];
                                            if($compte_type == CompteClient::TYPE_COMPTE_ETABLISSEMENT){
                                                $class_picto = "etablissement_picto";
                                            }
                                            if($compte_type == CompteClient::TYPE_COMPTE_SOCIETE){
                                                $class_picto = "societe_picto";
                                            }
											$texte_infobulle =  '<span>Type :</span> '. $societe_informations['type'].'<br /><span>Nom :</span> '.$societe_informations['raison_sociale'];
                                            ?>
                                            <td
												class="<?php echo $class_picto; ?>" data-contact-infos="<?php echo $texte_infobulle; ?>">
											</td>
					<td><a href="<?php echo url_for('compte_visualisation', array('identifiant' => $data['identifiant'])); ?>"><?php echo $data['nom_a_afficher']; ?></a></td>
					<td><?php echo $data['adresse']; ?>, <?php echo $data['code_postal']; ?>, <?php echo $data['commune']; ?></td>
					<td><?php echo $data['telephone_bureau']; ?> <?php echo $data['telephone_mobile'] ?> <?php echo $data['telephone_perso']; ?> <?php echo $data['fax']; ?></td>
					<td><?php echo $data['email']; ?></td>-->
				</div>

			<?php endforeach; ?>
		</div>

		<div class="pagination">
			<div class="page_precedente">
<?php if ($current_page > 1) : ?>
				<a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_precedente"> << </a>
				<?php if ($current_page > 1) $args['page'] = $current_page - 1; ?>
				<a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_precedente"> < </a>
<?php endif; ?>
			</div>
			<div class="page_suivante">
				<?php if ($current_page < $last_page) $args['page'] = $current_page + 1; else $args['page'] = $last_page ;?>
<?php if ($current_page != $args['page']): ?>
				<a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_precedente"> > </a>
<?php endif; ?>
				<?php $args['page'] = $last_page; ?>
<?php if ($current_page != $args['page']): ?>
                                <a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_suivante"> >> </a>
<?php endif; ?>
			</div>
		</div>
	
	<?php endif; ?>

</section>

        <div class="col-xs-3">
        <a class="btn_majeur btn_excel" href="<?php echo url_for('compte_search_csv', array('q' => $q, 'tags' => $args['tags'])); ?>">Exporter en CSV</a><br />
        <?php echo $nb_results; ?> résultat(s) trouvé(s)
        </div>

		<?php if (count($selected_typetags)) :  ?>
			<div class="col-xs-3">
				<h2>Tags sélectionnés</h2>
						<?php foreach($selected_typetags as $type => $selected_tags) : ?>
                            <h3><?php echo ucfirst($type); ?></h3>
							<div class="list-group">
								<?php foreach($selected_tags as $t) {
									$targs = $args_copy->getRawValue();
									$targs['tags'] = implode(',', array_diff($selected_rawtags->getRawValue(), array($type.':'.$t)));
									echo '<a class="list-group-item" href="'.url_for('compte_search', $targs).'">'.str_replace('_', ' ', $t).'</a>';
									$targs = $args_copy->getRawValue();
									$targs['tag'] = $t;
									if ($type == 'manuel') {
									  echo '(<a class="removetag" href="'.url_for('compte_removetag', $targs).'">X</a>)';
									}
									echo '';
									} ?>
							</div>
						<?php endforeach ?>
			</div>
		<?php endif; ?>

		<div class="col-xs-3">
			<h2>Tags disponibles</h2>
                <?php foreach($facets as $type => $ftype): ?>
                    <?php if (count($ftype['terms'])): ?>
                    <h3><?php echo ucfirst($type) ?></h3>
			           <div class="list-group">
                        <?php foreach($ftype['terms'] as $f): ?>
                            <?php if (preg_match('/^(export|produit)_/', $f['term'])) { continue; } ?>

        					<?php $targs = $args_copy->getRawValue(); ?>
        					<?php $targs['tags'] = implode(',', array_merge($selected_rawtags->getRawValue(), array($type.':'.$f['term']))); ?>
        					  <a class="list-group-item" href="<?php echo url_for('compte_search', $targs) ?>"><?php echo str_replace('_', ' ', $f['term']) ?> <span class="badge"><?php echo $f['count'] ?></span></a>
    					<?php endforeach; ?>
    					</div>
				    <?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>

                <?php if(isset($args_copy)): ?>
		<div class="col-xs-3">
			<h2>Créer un tag</h2>
			<form class="form_ajout_tag" action="<?php echo url_for('compte_addtag', $args_copy->getRawValue()); ?>" method="GET">
            <div class="input-group">
                <input id="creer_tag" name="tag" class="form-control" type="text" />
    			<span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-plus"></span></button>
                </span>
            </div>

			<input type="hidden" name="q" value="<?php echo $q;?>"/>
			<input type="hidden" name="tags" value="<?php echo implode(',', $selected_rawtags->getRawValue()); ?>"/>
			</form>
		</div>
                <?php endif; ?>
