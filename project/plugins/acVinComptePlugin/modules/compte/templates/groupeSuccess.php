<?php use_helper('Compte'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <li><a href="<?php echo url_for('compte_groupes'); ?>">Groupes</a></li>
    <li class="active"><a href="<?php echo url_for('compte_groupe', array("groupeName" => str_replace('.','!',sfOutputEscaper::unescape($groupeName)))); ?>"><?php echo str_replace('_',' ',$groupeName); ?></a></li>
</ol>
<div class="row">
  <div class="col-xs-12">
    <div class="panel panel-default">
          <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-11 ">
                      <h4>Détail du groupe « <?php echo str_replace('_',' ',$groupeName); ?> » <span class="badge"><?php echo count($results); ?></span></h4>
                  </div>
                  <div class="col-xs-1">
                    <a href="<?php echo url_for('compte_search', array('tags' => $filtre,'contacts_all' => true)) ; ?>"><span class="glyphicon glyphicon-search"></span></a> &nbsp;
                    <a href="<?php echo url_for('compte_search_csv', array('tags' => $filtre,'contacts_all' => true)) ; ?>"><span class="glyphicon glyphicon-export"></span></a>
                  </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="list-group" id="list-item">
              <?php foreach($results as $res):
                      $data = $res->getData();
                      $rawValue = $data['doc']['groupes']->getRawValue();
                      $fct = "";
                      foreach ($rawValue as $grp) {
                        if($grp["nom"] == sfOutputEscaper::unescape($groupeName)){
                          $fct = $grp["fonction"];
                          break;
                        }
                      }
                 ?>
                      <?php $societe_informations = (isset($data['doc']['societe_informations'])) ? $data['doc']['societe_informations'] : null; ?>
                      <div class="list-group-item <?php if ($data['doc']['statut'] != 'ACTIF') echo 'disabled'; ?>">
                          <div class="row">
                          <div class="col-xs-6">
                              <?php if($data['doc']['compte_type'] == 'INTERLOCUTEUR'): ?><small class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?php if(isset($societe_informations['raison_sociale'])): echo $societe_informations['raison_sociale']; endif; ?></small><br/><?php endif; ?>
                              <span class="lead"><span class="<?php echo comptePictoCssClass($data['doc']) ?>"></span></span>
                              <a class="lead" href="<?php echo url_for('compte_visualisation', array('identifiant' => $data['doc']['identifiant'])); ?>"><?php echo $data['doc']['nom_a_afficher']; ?></a> <span class="text-muted"><?php echo $data['doc']['identifiant']; ?></span>
                              </span>
                         </div>
                         <div class="col-xs-4 text-right">
                               <small class="text-muted label label-primary"><?php  echo $fct; ?></small>
                          </div>
                          <div class="col-xs-2 text-right">
                                <a class="btn btn-default" href="<?php echo url_for('compte_removegroupe', array('identifiant' => $data['doc']['identifiant'], 'groupeName' => str_replace(".",'!',sfOutputEscaper::unescape($groupeName)))); ?>"><span class="glyphicon glyphicon-trash"></span></a>
                           </div>
                        </div>
                      </div>
              <?php endforeach; ?>
            </div>
        </div>
      </div>
  </div>
  <div class="col-xs-12">
    <div class="panel panel-default">
          <div class="panel-heading">
              <div class="row">
                  <div class="col-xs-12 ">
                      <h4>Ajout d'un compte au groupe « <?php echo str_replace('_',' ',$groupeName); ?> »</h4>
                  </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="list-group" id="list-item">
                <h3>Sélection d'un opérateur</h3>
                <form method="post" class="form-horizontal" action="<?php echo url_for('compte_groupe',array('groupeName' => str_replace('.','!',sfOutputEscaper::unescape($groupeName)))); ?>">
                    <?php echo $form->renderHiddenFields() ?>
                    <?php echo $form->renderGlobalErrors() ?>
                    <div class="col-xs-7" style="margin-right: 10px;">
                      <div class="form-group <?php if($form['id_compte']->hasError()): ?> has-error<?php endif; ?>">
                          <?php echo $form['id_compte']->renderError(); ?>
                          <?php echo $form['id_compte']->render(array('class' => 'form-control select2autocompleteAjax', 'placeholder' => 'Rechercher')); ?>
                      </div>
                    </div>
                    <div class="col-xs-2">
                      <div class="form-group <?php if($form['fonction']->hasError()): ?> has-error<?php endif; ?>">
                          <?php echo $form["fonction"]->renderError(); ?>
                              <?php echo $form["fonction"]->render(array("class" => "form-control select2 select2permissifNoAjax",
                              "placeholder" => "Ajouter la fonction (liste permissive)",
                              "data-choices" => json_encode($form->getFonctionsForAutocomplete())));
                              ?>
                      </div>
                    </div>
                    <div class="col-xs-2">
                    <button class="btn btn-default btn-md" type="submit" id="btn_rechercher">Ajouter</button>
                    </div>
                </form>


            </div>
        </div>
      </div>
    </div>
</div>
