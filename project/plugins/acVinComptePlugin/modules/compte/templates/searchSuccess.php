<?php use_helper('Compte') ?>

<ol class="breadcrumb">
    <li class="active"><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
</ol>

<script type="text/javascript">
  window.onload = function () {
   $(document).ready(function() {
   $(".removetag").click(function() {
       return confirm('Etes vous sur(e) de vouloir supprimer définivement ce tag pour ces <?php echo $nb_results; ?> fiches ?');
     });
   $("#contacts_all").click(function () { $('#recherche_contact_form').submit(); });

   $(".plus-tags").click(function(){
     var siblings = $(this).siblings(".tag_hidden");
     if(siblings.is(":visible")){
       siblings.hide();
       $(this).children("span").addClass('glyphicon-chevron-down').removeClass('glyphicon-chevron-up');
     }else{
       siblings.show();
       $(this).children("span").addClass('glyphicon-chevron-up').removeClass('glyphicon-chevron-down');
     }
   });
   });
  });
</script>
<div class="row">
    <section class="col-xs-12 col-sm-8 col-md-9" id="contenu_etape">
		<form id="recherche_contact_form">
			<div id="recherche_contact" class="section_label_maj">
        <div class="input-group">
            <input id="champ_recherche" name="q" value = "<?php echo $q ?>" class="form-control input-lg typeahead typeaheadGlobal"
            placeholder = "Votre recherche..." autocomplete = "off" data-url = "<?php echo url_for('soc_etb_com_autocomplete_actif', array('link' => true, 'interpro_id'=> 'INTERPRO-declaration','type_compte' => '*')); ?>"
            data-query-param = "q" type="text" data-visualisationLink = "<?php echo url_for('compte_visualisation', array('identifiant' => "identifiant")); ?>"  data-link = "visualisationLink" autofocus="autofocus" data-text = "text_html" />
            <span class="input-group-btn">
                <button class="btn btn-lg btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </span>
        </div>
        <div>
            <label for="contacts_all">Inclure les contacts archivés </label>
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
            	<?php if ($contacts_all) { $args['contacts_all'] = 1; } ?>
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

    <div class="list-group" id="list-item">
		<?php foreach($results as $res): ?>
			<?php $data = $res->getData(); ?>
            <?php $societe_informations = (isset($data['doc']['societe_informations'])) ? $data['doc']['societe_informations'] : null; ?>
			<div class="list-group-item <?php if ($data['doc']['statut'] != 'ACTIF') echo 'disabled'; ?> <?php if (isset($data['doc']['en_alerte']) && $data['doc']['en_alerte']) echo 'en_alerte'; ?>">
                <div class="row">
                <div class="col-xs-9">
                    <?php if($data['doc']['compte_type'] == 'INTERLOCUTEUR'): ?><small class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?php if(isset($societe_informations['raison_sociale'])): echo Anonymization::hideIfNeeded($societe_informations['raison_sociale']); endif; ?></small><br/><?php endif; ?>
                    <span class="lead"><span class="<?php echo comptePictoCssClass($data['doc']) ?>"></span></span>
                    <a class="lead" href="<?php echo url_for('compte_visualisation', array('identifiant' => $data['doc']['identifiant'])); ?>"><?php echo ($data['doc']['nom_a_afficher'])? Anonymization::hideIfNeeded($data['doc']['nom_a_afficher']) : "inconnu"; ?></a> <span class="text-muted"><?php echo $data['doc']['identifiant']; ?></span>
                    <?php if($data['doc']['compte_type'] == 'ETABLISSEMENT' && isset($data['doc']['etablissement_informations']['cvi']) && $data['doc']['etablissement_informations']['cvi']): ?><span class="text-muted">- <?php echo $data['doc']['etablissement_informations']['cvi']; ?></span><?php elseif($data['doc']['compte_type'] != 'INTERLOCUTEUR' && isset($data['doc']['societe_informations']['siret']) && $data['doc']['societe_informations']['siret']): ?><span class="text-muted">- <?php echo formatSIRET($data['doc']['societe_informations']['siret']); ?></span><?php endif; ?>
                    <?php if (isset($data['doc']['en_alerte']) && $data['doc']['en_alerte']) echo ' ⛔'; ?>
                    </span>
               </div><div class="col-xs-3 text-right">
<?php if(isset($societe_informations['type']) && $societe_informations['type']): ?><small class="text-muted label label-primary"><?php echo $societe_informations['type'] ?></small><?php endif; if ($data['doc']['statut'] != 'ACTIF') echo ' &nbsp; <small class="text-muted label label-default">'.CompteClient::$statutsLibelles[$data['doc']['statut']].'</small>'; ?>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <?php echo Anonymization::hideIfNeeded($data['doc']['adresse']); ?> <?php if ($data['doc']['adresse_complementaire']): ?><small>(<?php echo Anonymization::hideIfNeeded($data['doc']['adresse_complementaire']); ?>)</small><?php endif; ?><br />
                    <?php echo $data['doc']['code_postal']; ?> <?php echo $data['doc']['commune']; ?><br />

                </div>
                <div class="col-xs-12 col-sm-6">
                    <ul class="list-unstyled" style="margin-bottom: 0;">
                        <?php if($data['doc']['telephone_bureau']): ?>
                        <li>Bureau : <a href="callto:<?php echo Anonymization::hideIfNeeded($data['doc']['telephone_bureau']); ?>"><?php echo Anonymization::hideIfNeeded($data['doc']['telephone_bureau']); ?></a></li>
                        <?php endif; ?>
                        <?php if($data['doc']['telephone_mobile']): ?>
                        <li>Mobile : <a href="callto:<?php echo Anonymization::hideIfNeeded($data['doc']['telephone_mobile']); ?>"><?php echo Anonymization::hideIfNeeded($data['doc']['telephone_mobile']); ?></a></li>
                        <?php endif; ?>
                        <?php if($data['doc']['telephone_perso']): ?>
                        <li>Perso : <a href="callto:<?php echo Anonymization::hideIfNeeded($data['doc']['telephone_perso']); ?>"><?php echo Anonymization::hideIfNeeded($data['doc']['telephone_perso']); ?></a></li>
                        <?php endif; ?>
                        <?php if($data['doc']['email']):
                            foreach (explode(';',$data['doc']['email']) as $email): ?>
                            <li><a href="mailto:<?php echo Anonymization::hideIfNeeded($email); ?>"><?php echo Anonymization::hideIfNeeded($email); ?></a></li>
                        <?php endforeach; ?>
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
<section class="col-xs-12 col-sm-4 col-md-3" style="padding-bottom: 20px;">
    	<a class="btn btn-default btn-default-step btn-block" href="<?php echo url_for("compte_recherche_avancee") ?>"><span class="glyphicon glyphicon-zoom-in"></span>&nbsp;&nbsp;Recherche avancée</a>
        <a href="<?php echo url_for('societe_creation', array()); ?>" class="btn btn-default btn-block"><span class="glyphicon glyphicon-plus"></span> Créer une société</a>
        <a class="btn btn-default btn-block" href="<?php echo url_for('compte_search_csv', array('q' => $q, 'tags' => $args['tags'], 'contacts_all' => ($contacts_all)? 1 : 0)); ?>"<?php if($nb_results > 50000): ?> disabled="disabled"<?php endif;?>> <span class="glyphicon glyphicon-export"></span> Exporter en CSV</a>
      <a class="btn btn-default btn-block" href="<?php echo url_for('compte_groupes') ?>" > <span class="glyphicon glyphicon-tags"></span>&nbsp;&nbsp;Gérer les groupes</a>
      <p style="margin-top: 10px;"><strong><?php echo $nb_results; ?></strong> résultat(s) trouvé(s)</p>

	<div style="margin-top: 15px;">
    <?php
    foreach($facets as $type => $ftype):
      if (count($ftype['buckets'])): ?>
           <?php foreach($ftype['buckets'] as $f):
             $targs = $args_copy->getRawValue();
             $sargs = $args_copy->getRawValue();
             $sargs['tags'] = implode(',', array_diff($selected_rawtags->getRawValue(), array($type.':'.$f['key'])));
          if($type == 'groupes' && (isset($selected_typetags->getRawValue()[$type]) && in_array($f['key'], $selected_typetags->getRawValue()[$type]))): ?>
          <div class="list-group">
            <a class="list-group-item list-group-item-xs active" href="<?php echo url_for('compte_search', $sargs); ?>"><?php echo str_replace('_', ' ', $f['key']) ?> <span class="badge" style="position: absolute; right: 10px;"><?php echo $f['doc_count'] ?></span></a>
          </div>
        <?php endif;
        endforeach;
      endif;
    endforeach;
    if($contacts_all && isset($sargs)):
      		$sargs_archived = $sargs;
      		unset($sargs_archived["contacts_all"]);
    ?>
    <div class="list-group">
      <a class="list-group-item list-group-item-xs active" href="<?php echo url_for('compte_search', $sargs_archived) ?>" >Avec archivés</a>
    </div>
    <?php endif; ?>

            <?php $tagsManuels = array(); ?>
            <?php $max_tags = 5; ?>
            <?php foreach($facets as $type => $ftype): ?>
                <?php $cptTags = 1; ?>
                <?php if (count($ftype['buckets'])): ?>
                  <?php if($type == 'groupes'){ continue; } ?>
                <h4>Tags <?php echo $type ?></h4>
		           <div class="list-group">
                    <?php foreach($ftype['buckets'] as $f): ?>
                        <?php if (preg_match('/^(export|produit)_/', $f['key'])) { continue; } ?>
    					<?php
    						$active = (isset($selected_typetags->getRawValue()[$type]) && in_array($f['key'], $selected_typetags->getRawValue()[$type]))? 'active' : '';
                            $count = $f['doc_count'];
                            $not = '';
                            if (($f['doc_count'] < 0)) {
                                $active = 'active';
                                $count = '!';
                                $not = '!';
                            }
                            $targs = $args_copy->getRawValue();
                            $sargs = $args_copy->getRawValue();
    						$targs['tags'] = implode(',', array_merge($selected_rawtags->getRawValue(), array($type.':'.$f['key'])));
    						$sargs['tags'] = implode(',', array_diff($selected_rawtags->getRawValue(), array($not.$type.':'.$f['key'])));
    						if ($type == 'manuel') {
    							$tagsManuels[] = $f['key'];
    						}
    					?>
                        <a <?php if($cptTags > $max_tags): echo "style='display:none;'"; endif; ?>
                        <?php if($active && !$not): ?>
    					  onclick="if(confirm('Souhaitez-vous voir les comptes sans tag <?php echo $f['key']; ?> ?')){ document.location = '<?php echo url_for('compte_search', $sargs).",!".$type.':'.$f['key']; ?>'; return false; }"
                        <?php endif; ?>
                        class="list-group-item list-group-item-xs <?php echo $active ?> <?php if($cptTags > $max_tags): echo 'tag_hidden'; endif; ?>" href="<?php echo ($active)? url_for('compte_search', $sargs) : url_for('compte_search', $targs); ?>"><?php echo str_replace('_', ' ', $f['key']) ?>
                            <span class="badge" style="position: absolute; right: 10px;"><?php echo $count; ?></span></a>
          <?php $cptTags++; ?>
					<?php endforeach; ?>
          <a class="list-group-item list-group-item-xs  plus-tags text-center" style ><span class="glyphicon glyphicon-chevron-down"></span></a>
					</div>
			    <?php endif; ?>
			<?php endforeach; ?>
	</div>

    <?php if(isset($args_copy)): ?>
	<div style="margin-top: 15px;">
		<h4>Créer un tag</h4>
		<form class="form_ajout_tag" action="<?php echo url_for('compte_addtag', $args_copy->getRawValue()); ?>" method="GET">
        <div class="input-group">
            <input id="creer_tag" required="required" name="tag" class="tags form-control select2 select2permissifNoAjax" placeholder="Saisir le nom du tag" data-choices='<?php echo json_encode(CompteClient::getInstance()->getAllTagsManuel()); ?>'  type="text">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-plus"></span></button>
            </span>
        </div>

		<input type="hidden" name="q" value="<?php echo $q;?>"/>
		<input type="hidden" name="tags" value="<?php echo implode(',', $selected_rawtags->getRawValue()); ?>"/>
		</form>
	</div>
	<?php if ($tagsManuels): ?>
	<div style="margin-top: 15px;">
		<h4>Supprimer un tag</h4>
		<form class="form_ajout_tag" action="<?php echo url_for('compte_removetag', $args_copy->getRawValue()); ?>" method="GET">
        <div class="input-group">
            <select id="suppr_tag" placeholder="Sélectionner un tag" required="required" name="tag" class="form-control select2">
            	<option value=""></option>
            	<?php foreach ($tagsManuels as $tm): ?>
            	<option value="<?php echo $tm ?>"><?php echo $tm ?></option>
            	<?php endforeach; ?>
            </select>
			<span class="input-group-btn">
                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-remove"></span></button>
            </span>
        </div>

		<input type="hidden" name="q" value="<?php echo $q;?>"/>
		<input type="hidden" name="tags" value="<?php echo implode(',', $selected_rawtags->getRawValue()); ?>"/>
		</form>
	</div>
	<?php endif; ?>
    <?php endif; ?>
</section>
</div>
