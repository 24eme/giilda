<?php use_helper('Compte') ?>

<ol class="breadcrumb">
    <li class="active"><a href="<?php echo url_for('societe') ?>">Accueil des contacts</a></li>
</ol>

<script type="text/javascript">
   $(document).ready(function() {
   $(".removetag").click(function() {
       return confirm('Etes vous sur(e) de vouloir supprimer définivement ce tag pour ces <?php echo $nb_results; ?> fiches ?');
     });
     });
</script>
<div class="row">
    <section class="col-xs-9" id="contenu_etape">
		<form>
			<div id="recherche_contact" class="section_label_maj">
				
                <div class="input-group">
                    <input id="champ_recherche" class="form-control input-lg" type="text" name="q" value="<?php echo $q; ?>"> 
                    <span class="input-group-btn">
                        <button class="btn btn-lg btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
                <div>
                    <label for="contacts_all">Inclure les contacts suspendus </label>
                    <input type="checkbox" value="1" name="contacts_all" id="contacts_all"<?php if($contacts_all) echo " CHECKED"; ?>/>
                </div>
			</div>
		</form>

	<?php if($nb_results > 0): ?>
	<div class="text-center">
        <nav>	
    		<ul class="pagination">
                <?php $args_copy = $args; ?>
                <?php $args = array('q' => $q, 'tags' => $args['tags']); ?>
                <?php if ($current_page > 1) : ?>
    				<li><a href="<?php echo url_for('compte_search', $args); ?>"><span aria-hidden="true"><<</span></a></li>
    				<?php if ($current_page > 1) $args['page'] = $current_page - 1; ?>
    				<li><a href="<?php echo url_for('compte_search', $args); ?>"><span aria-hidden="true"><</span></a></li>
                <?php endif; ?>
    			<?php if ($current_page < $last_page) $args['page'] = $current_page + 1; else $args['page'] = $last_page ;?>
                <li><a href="">page <?php echo $current_page; ?> sur <?php echo $last_page; ?></a></li>
                <?php if ($current_page != $args['page']): ?>
                	<li><a href="<?php echo url_for('compte_search', $args); ?>"> > </a></li>
                <?php endif; ?>
    				<?php $args['page'] = $last_page; ?>
                <?php if ($current_page != $args['page']): ?>
                    <li><a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_suivante"> >> </a></li>
                <?php endif; ?>
    		</ul>
        </nav>
    </div>
	<?php $cpt = 0; ?>

    <div class="list-group">
		<?php foreach($results as $res): ?>
			<?php $data = $res->getData(); ?>
            <?php $societe_informations = $data['societe_informations']; ?>
			<div class="list-group-item">
                <div class="row">
                <div class="col-xs-8">
                    <span class="lead"><span class="<?php echo comptePictoCssClass($data) ?>"></span></span>
                    <a class="lead" href="<?php echo url_for('compte_visualisation', array('identifiant' => $data['identifiant'])); ?>"><?php echo $data['nom_a_afficher']; ?></a>
                    <?php if($data['compte_type'] == 'INTERLOCUTEUR'): ?><small class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?php echo $societe_informations['raison_sociale'] ?></small>
                    <?php endif; ?></span>
                    <?php if($societe_informations['type']): ?><small class="text-muted">(<?php echo $societe_informations['type'] ?>)</small><?php endif; ?>
                    <br />
                    <?php echo $data['adresse']; ?> <?php if ($data['adresse_complementaire']): ?><small>(<?php echo $data['adresse_complementaire']; ?>)</small><?php endif; ?><br />
                    <?php echo $data['code_postal']; ?> <?php echo $data['commune']; ?><br />
                    
                </div>
                <div class="col-xs-4">
                    <ul class="list-unstyled" style="margin-bottom: 0;">
                        <?php if($data['telephone_bureau']): ?>
                        <li>Bureau : <a href="callto:<?php echo $data['telephone_bureau'] ?>"><?php echo $data['telephone_bureau'] ?></a></li>
                        <?php endif; ?>
                        <?php if($data['telephone_mobile']): ?>
                        <li>Mobile : <a href="callto:<?php echo $data['telephone_mobile'] ?>"><?php echo $data['telephone_mobile'] ?></a></li>
                        <?php endif; ?>
                        <?php if($data['telephone_perso']): ?>
                        <li>Perso : <a href="callto:<?php echo $data['telephone_perso'] ?>"><?php echo $data['telephone_perso'] ?></a></li>
                        <?php endif; ?>
                        <?php if($data['email']): ?>
                            <li><a href="mailto:<?php echo $data['email']; ?>"><?php echo $data['email']; ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                </div>
			</div>

		<?php endforeach; ?>
	</div>

    <div class="text-center">
        <nav>   
            <ul class="pagination">
                <?php if ($current_page > 1) : ?>
                    <li><a href="<?php echo url_for('compte_search', $args); ?>"><span aria-hidden="true"><<</span></a></li>
                    <?php if ($current_page > 1) $args['page'] = $current_page - 1; ?>
                    <li><a href="<?php echo url_for('compte_search', $args); ?>"><span aria-hidden="true"><</span></a></li>
                <?php endif; ?>
                <?php if ($current_page < $last_page) $args['page'] = $current_page + 1; else $args['page'] = $last_page ;?>
                <li><a href="">page <?php echo $current_page; ?> sur <?php echo $last_page; ?></a></li>
                <?php if ($current_page != $args['page']): ?>
                    <li><a href="<?php echo url_for('compte_search', $args); ?>"> > </a></li>
                <?php endif; ?>
                    <?php $args['page'] = $last_page; ?>
                <?php if ($current_page != $args['page']): ?>
                    <li><a href="<?php echo url_for('compte_search', $args); ?>" class="btn_majeur page_suivante"> >> </a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
	
	<?php endif; ?>

</section>
    <div class="col-xs-3">
        <a href="<?php echo url_for('societe_creation', array('identifiant' => $societe->identifiant)); ?>" class="btn btn-default btn-block"><span class="glyphicon glyphicon-plus"></span> Créer une société</a> 
     <a class="btn btn-default btn-block" href="<?php echo url_for('compte_search_csv', array('q' => $q, 'tags' => $args['tags'])); ?>"> <span class="glyphicon glyphicon-export"></span> Exporter en CSV</a>
     <br />
        <span class="lead"><?php echo $nb_results; ?> résultat(s) trouvé(s)</span>
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
								echo '<a class="list-group-item list-group-item-xs" href="'.url_for('compte_search', $targs).'">'.str_replace('_', ' ', $t).'</a>';
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
    					  <a class="list-group-item list-group-item-xs" href="<?php echo url_for('compte_search', $targs) ?>"><?php echo str_replace('_', ' ', $f['term']) ?> <span class="badge"><?php echo $f['count'] ?></span></a>
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
</div>