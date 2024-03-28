<?php use_helper('Compte') ?>
<?php $typesLiaisons = EtablissementClient::getTypesLiaisons(); ?>
<ol class="breadcrumb">
    <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($societe->getRawValue()) ?>"></span> <?php echo $societe->raison_sociale; ?></a></li>
    <li><a href="<?php echo url_for('etablissement_visualisation', array('identifiant' => $etablissement->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($etablissement->getRawValue()) ?>"></span> <?php echo $etablissement->nom; ?></a></li>
    <li class="active"><a href="">Ajout d'une relation</a></li>
</ol>

<div class="row">
    <form action="<?php echo url_for("etablissement_ajout_relation_chai", array('identifiant' => $etablissement->identifiant, 'id_etablissement' => $etablissementRelation->_id, 'type_liaison' => $typeLiaison)) ?>" method="post" class="form-horizontal">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>
        <div class="col-xs-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>1. Ajout d'une relation</h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                       <label class="col-sm-4 control-label">Établissement :</label>
                       <div class="col-sm-7">
                         <p class="form-control-static"><?php echo $etablissement->nom ?></p>
                       </div>
                     </div>
                    <div class="form-group">
                       <label class="col-sm-4 control-label">Relation :</label>
                       <div class="col-sm-7">
                         <p class="form-control-static"><?php echo $typesLiaisons[$typeLiaison]; ?></p>
                       </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-4 control-label">Avec l'établissement :</label>
                        <div class="col-sm-7">
                          <p class="form-control-static"><?php echo $etablissementRelation->raison_sociale ?></p>
                        </div>
                      </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>2. Chai de destination <small>(chez <?php echo $etablissementChai->raison_sociale ?>)</small></h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <?php echo $form['hash_chai']->renderError(); ?>
                        <?php echo $form['hash_chai']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                        <div class="col-xs-7"><?php echo $form['hash_chai']->render(array('class' => 'form-control')); ?></div>
                    </div>
                    <?php $attributsWidget = $form['attributs_chai']; ?>
                    <div class="form-group">
                        <div class="col-xs-4 control-label">
                            <label>Utilisation du chai :</label>
                        </div>
                        <div class="col-xs-8">
                            <?php foreach($attributsWidget->getWidget()->getChoices() as $key => $value): ?>
                            <div class="checkbox">
                              <label>
                                <input type="checkbox" id="<?php echo $attributsWidget->renderId() ?>_<?php echo $key ?>" name="<?php echo $attributsWidget->renderName() ?>[]" value="<?php echo $key ?>" <?php if(is_array($attributsWidget->getValue()) && in_array($key, array_keys($attributsWidget->getValue()))): ?>checked="checked"<?php endif; ?> />
                                <?php echo EtablissementClient::$chaisAttributsLibelles[$key]; ?>
                              </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-xs-4">
                            <a href="<?php echo url_for('etablissement_visualisation', array('identifiant' => $etablissement->identifiant)); ?>" class="btn btn-default">Annuler</a>
                        </div>
                        <div class="col-xs-4 text-center">
                        </div>
                        <div class="col-xs-4 text-right">
                            <button id="btn_valider" type="submit" class="btn btn-success">
                                Terminer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="col-xs-4">
        <?php include_component('societe', 'sidebar', array('societe' => $societe, 'activeObject' => $etablissement)); ?>
    </div>
</div>
