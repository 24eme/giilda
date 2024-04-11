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
                  <div class="col-xs-12 ">
                      <h4>Ajout d'un compte au groupe « <?php echo str_replace('_',' ',$groupeName); ?> »</h4>
                  </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="list-group" id="list-item">
                <h3>Ajout de l'opérateur</h3>
                <form method="post" class="form-horizontal" action="<?php echo url_for('compte_addingroupe',array('identifiant' => $compte->identifiant)); ?>">
                    <?php echo $form->renderHiddenFields() ?>
                    <?php echo $form->renderGlobalErrors() ?>
                    <div class="col-xs-7" style="margin-right: 10px;">
                      <h2>
                        <?php echo $compte->nom_a_afficher; ?>
                        <?php if ($compte->isSuspendu()): ?>
                    <span class="label label-default pull-right" style="padding-top: 0;"><small style="font-weight: inherit; color: inherit;"><?php echo $compte->getStatutLibelle(); ?></small></span>
                <?php endif; ?>
                      </h2>
                      <hr/>
                      <div class="row">
                          <div class="col-xs-5">
                              <div class="row">
                                  <?php if ($compte->identifiant): ?>
                                      <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">Identifiant&nbsp;:</div>
                                      <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo $compte->identifiant; ?></div>
                                  <?php endif; ?>
                                  <?php if ($compte->fonction): ?>
                                      <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">Fonction&nbsp;:</div>
                                      <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo $compte->fonction; ?></div>
                                  <?php endif; ?>
                              </div>
                          </div>
                          <div class="col-xs-7" style="border-left: 1px solid #eee">
                              <?php include_partial('compte/visualisationAdresse', array('compte' => $compte)); ?>
                          </div>
                      </div>
                      <hr />
                      <div class="form-group <?php if($form['id_compte']->hasError()): ?> has-error<?php endif; ?>" style="display:none;">
                          <?php echo $form['id_compte']->renderError(); ?>
                          <?php echo $form['id_compte']->render(array('readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => 'Rechercher')); ?>
                          <input name="groupe" value="<?php echo $groupe; ?>" >
                      </div>
                    </div>
                    <div class="col-xs-2">
                      <div class="form-group <?php if($form['fonction']->hasError()): ?> has-error<?php endif; ?>">
                          <?php echo $form["fonction"]->render(array("class" => "form-control select2 select2permissifNoAjax",
                            "placeholder" => "Ajouter la fonction (liste permissive)",  "autofocus" => "autofocus",
                            "data-choices" => json_encode($form->getFonctionsForAutocomplete())));
                            ?>
                      </div>
                    </div>
                    <div class="col-xs-2">
                    <button class="btn btn-default btn-md" type="submit" id="">Ajouter</button>
                    </div>
                </form>


            </div>
        </div>
      </div>
    </div>
</div>
