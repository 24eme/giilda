<?php use_helper('Compte') ?>
<ol class="breadcrumb">
    <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($societe->getRawValue()) ?>"></span> <?php echo $societe->raison_sociale; ?></a></li>
    <li><a href="<?php echo url_for('etablissement_visualisation', array('identifiant' => $etablissement->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($etablissement->getRawValue()) ?>"></span> <?php echo $etablissement->nom; ?></a></li>
    <li class="active"><a href="">Ajout d'une relation</a></li>
</ol>

<div class="row">
        <div class="col-xs-8">
            <form action="<?php echo url_for("etablissement_ajout_relation", array('identifiant' => $etablissement->identifiant)) ?>" method="post" class="form-horizontal">
                <?php echo $form->renderHiddenFields(); ?>
                <?php echo $form->renderGlobalErrors(); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>1. Ajout d'une relation</h4>
                </div>
                <div class="panel-body">
                    <div class="row" style="padding-top:10px;">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Établissement</label>
                            <div class="col-sm-4">
                                <p class="form-control-static"><?php echo $etablissement->nom ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-top:10px;">
                        <div class="form-group">
                            <?php echo $form['type_liaison']->renderError(); ?>
                            <?php echo $form['type_liaison']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                            <div class="col-xs-7"><?php echo $form['type_liaison']->render(array('class' => 'form-control')); ?></div>
                        </div>
                    </div>
                    <div class="row" style="padding-top:10px;">
                        <div class="form-group">
                            <?php echo $form['id_etablissement']->renderError(); ?>
                            <?php echo $form['id_etablissement']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                            <div class="col-xs-7"><?php echo $form['id_etablissement']->render(array('class' => 'form-control select2autocompleteAjax input-md', 'placeholder' => 'Séléctionner un établissement')); ?></div>
                        </div>
                    </div>
                    <div class="checkbox">
                       <span class="col-xs-offset-4">
                            <span class="glyphicon glyphicon-check"></span> Créer la relation inverse
                       </span>
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
                                Continuer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>2. Chai de destination</h4>
                </div>
            </div>
        </div>
    </form>
    <div class="col-xs-4">
        <?php include_component('societe', 'sidebar', array('societe' => $societe, 'activeObject' => $etablissement)); ?>
    </div>
</div>
