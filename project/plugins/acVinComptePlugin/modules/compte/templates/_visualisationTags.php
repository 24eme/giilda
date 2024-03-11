<?php $hasManuel = false; ?>
<?php $modifiable = !isset($modifiable) || $modifiable; ?>
    <div style="margin-bottom: 10px;">
      <div class="row" style="margin-bottom: 10px;">
        <div class="col-xs-2 text-muted">Groupes&nbsp;:</div>
        <div class="col-xs-10">
            <?php foreach($compte->getGroupesSortedNom() as $key => $grp) : ?>
                <?php if($modifiable): ?>
              <div class="btn-group" style="padding-bottom : 3px;">
                <a class="btn btn-sm btn-default" href="<?php echo url_for('compte_groupe', array("groupeName" => str_replace('.','!',sfOutputEscaper::unescape($grp['nom'])))); ?>"><?php echo $grp['nom']; ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo url_for('compte_groupe', array("groupeName" => str_replace('.','!',sfOutputEscaper::unescape($grp['nom'])))); ?>"><?php echo $grp['fonction']; ?></a>
                <a class="btn btn-sm btn-default" href="<?php echo url_for('compte_removegroupe', array("groupeName" => str_replace('.','!',sfOutputEscaper::unescape($grp['nom'])), "identifiant" => $compte->identifiant, "retour" => "visu")); ?>"><span class="glyphicon glyphicon-trash"/></a>
              </div>
              <?php else: ?>
                  <div class="btn-group" style="padding-bottom : 3px;">
                    <button class="btn btn-sm btn-default"><?php echo $grp['nom']; ?></button>
                    <button class="btn btn-sm btn-primary"><?php echo $grp['fonction']; ?></button>
                  </div>
              <?php endif; ?>
              <br/>
            <?php endforeach; ?>
            <?php if(isset($formAjoutGroupe) && $modifiable): ?>
              <form id="form_ajout_groupe" method="GET" class="form-horizontal" action="<?php echo url_for('compte_addingroupe',array('identifiant'=> $compte->getIdentifiant())); ?>">
                  <?php echo $formAjoutGroupe->renderHiddenFields() ?>
                  <?php echo $formAjoutGroupe->renderGlobalErrors() ?>
                  <div class="btn-group">
                    <input type="hidden" name="compte_groupe_ajout[id_compte]" value="COMPTE-<?php echo $compte->identifiant;?>"/>
                    <div class="input-group input-group-sm col-xs-12">
                      <input id="ajout_groupe" name="groupe" class="tags form-control select2 select2permissifNoAjax" placeholder="Ajouter un le compte dans un groupe" data-choices='<?php echo json_encode(CompteClient::getInstance()->getAllTagsGroupes($compte->groupes),JSON_HEX_APOS); ?>'    type="text">
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">&nbsp;<span class="glyphicon glyphicon-plus"></span></button>
                      </span>
                    </div>
                    <input type="hidden" name="retour" value="<?php echo url_for('compte_visualisation', $compte) ?>"/>
                </div>
              </form>
             <?php endif; ?>
        </div>
      </div>
      <?php foreach ($compte->tags as $type_tag => $tags) :
        if ($type_tag == 'groupes') {continue;}
        ?>
        <div class="row" style="margin-bottom: 10px;">
          <div class="col-xs-2 text-muted"><?php echo ucfirst($type_tag) ?>&nbsp;:</div>
          <div class="col-xs-10">
            <?php if($modifiable): ?>
            <?php foreach ($tags as $t): ?>
                <div class="btn-group">
                    <a class="btn btn-sm <?php if($type_tag == "automatique"): ?>btn-link<?php endif; ?> <?php if($type_tag == "manuel"): ?>btn-default<?php endif; ?>"
                      href="<?php echo url_for('compte_search', array('tags' => implode(',', array($type_tag . ':' . $t)))); ?>">
                      <?php echo ucfirst(str_replace('_', ' ', $t)) ?>
                    </a>
                    <?php if ($type_tag == 'manuel'): ?><a class="btn btn-sm btn-default" href="<?php echo url_for('compte_removetag', array('q' => "doc.identifiant:".$compte->identifiant, 'tag' => $t, 'retour'=>url_for('compte_visualisation', $compte))) ?>"><span class="glyphicon glyphicon-trash"></span></a><?php endif; ?></span>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($tags as $t): ?>
                    <small style="margin-right: 5px"><?php echo ucfirst(str_replace('_', ' ', $t)) ?></small>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <?php if($type_tag == 'manuel' && $modifiable): ?>
              <?php $hasManuel = true; ?>
                <div class="btn-group">
                  <?php if ($compte->isSuspendu() || $compte->getSociete()->isSuspendu()):
                    echo "<span class='text-muted'> Ajout de tag impossible pour un contact archivé</span>";
                  else: ?>
                  <form class="form_ajout_tag" action="<?php echo url_for('compte_addtag', array("q" => "doc.identifiant:".$compte->identifiant)); ?>" method="GET">
                    <div class="input-group input-group-sm col-xs-12">
                      <input id="creer_tag" name="tag" class="tags form-control select2 select2permissifNoAjax" placeholder="Ajouter un tag (liste permissive)" data-choices='<?php echo json_encode(CompteClient::getInstance()->getAllTagsManuel()); ?>'    type="text">

                      <input type="hidden" name="q" value="doc.identifiant:<?php echo $compte->identifiant;?>"/>
                      <input type="hidden" name="retour" value="<?php echo url_for('compte_visualisation', $compte) ?>"/>
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">&nbsp;<span class="glyphicon glyphicon-plus"></span></button>
                      </span>
                    </div>
                  </form>
                <?php endif; ?>
                </div>
            <?php endif; ?>&nbsp;
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(!$hasManuel && $modifiable): ?>
      <div class="row" style="margin-bottom: 5px;">
        <div class="col-xs-2 text-muted">Manuel&nbsp;:</div>
        <div class="col-xs-10">
            <div class="btn-group">
              <?php if ($compte->isSuspendu() || $compte->getSociete()->isSuspendu()):
                echo "<span class='text-muted'> Ajout de tag impossible pour un contact archivé</span>";
              else: ?>
              <form class="form_ajout_tag" action="<?php echo url_for('compte_addtag', array("q" => "doc.identifiant:".$compte->identifiant)); ?>" method="GET">
                <div class="input-group input-group-sm col-xs-12">
                  <input id="creer_tag" required="required" name="tag" class="tags form-control select2 select2permissifNoAjax" placeholder="Ajouter un tag (liste permissive)" data-choices='<?php echo json_encode(CompteClient::getInstance()->getAllTagsManuel()); ?>'  type="text">
                  <input type="hidden" name="q" value="doc.identifiant:<?php echo $compte->identifiant;?>"/>
                  <input type="hidden" name="retour" value="<?php echo url_for('compte_visualisation', $compte) ?>"/>
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">&nbsp;<span class="glyphicon glyphicon-plus"></span></button>
                  </span>
                </div>
              </form>
            <?php endif; ?>
            </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
