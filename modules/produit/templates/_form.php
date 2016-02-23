<form id="form_ajout" class="form-horizontal" action="<?php echo url_for('produit_modification', array('noeud' => $form->getObject()->getTypeNoeud(), 'hash' => $produit->getHashForKey())) ?>" method="post">
    <?php echo $form->renderGlobalErrors() ?>
    <?php echo $form->renderHiddenFields() ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><label>Code / Libellé</label></h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 " id="produit_libelle">
                            <div class="col-sm-12">
                                <?php echo $form['libelle']->renderError() ?>
                                <div class="form-group <?php if ($form['libelle']->hasError()): ?>has-error<?php endif; ?>" >
                                    <?php echo $form['libelle']->render(array('class' => 'form-control', 'placeholder' => 'Libellé')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 " id="produit_format_libelle">
                            <div class="col-sm-12">
                                <?php echo $form['format_libelle']->renderError() ?>
                                <div class="form-group <?php if ($form['format_libelle']->hasError()): ?>has-error<?php endif; ?>" >
                                    <?php echo $form['format_libelle']->render(array('class' => 'form-control', 'placeholder' => 'Format du Libellé')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12">
                                <strong>Clé :</strong>
                                <span><?php echo $form->getObject()->getKey(); ?></span><br/>
                                <span class="text-muted">Cette clé est utilisée pour construire l'arbre, elle est constituante du hash produit</span>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-sm-12 " id="produit_code">
                            <div class="col-sm-12">
                                <?php echo $form['code']->renderError() ?>
                                <div class="form-group <?php if ($form['code']->hasError()): ?>has-error<?php endif; ?>" >
                                    <?php echo $form['code']->render(array('class' => 'form-control', 'placeholder' => "Code utilisé par l'interpro (il en général identique à la clé sauf pour les couleurs)")); ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <?php if ($form->getObject()->exist('densite')): ?>
                        <div class="row">
                            <div class="col-sm-12 " id="produit_densite">
                                <div class="col-sm-12">
                                    <?php echo $form['densite']->renderError() ?>
                                    <div class="form-group <?php if ($form['densite']->hasError()): ?>has-error<?php endif; ?>" >
                                        <?php echo $form['densite']->render(array('class' => 'form-control', 'placeholder' => 'Densité')); ?>
                                        <span class="text-muted"><?php echo $form['densite']->renderHelp() ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($form->getObject()->hasCodes()): ?>
                        <div class="row">
                            <div class="col-sm-12 " id="produit_code_produit">
                                <div class="col-sm-12">
                                    <?php echo $form['code_produit']->renderError() ?>
                                    <div class="form-group <?php if ($form['code_produit']->hasError()): ?>has-error<?php endif; ?>" >
                                        <?php echo $form['code_produit']->render(array('class' => 'form-control', 'placeholder' => 'Code produit')); ?>

                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-sm-12 " id="produit_code_douane">
                                <div class="col-sm-12">
                                    <?php echo $form['code_douane']->renderError() ?>
                                    <div class="form-group <?php if ($form['code_douane']->hasError()): ?>has-error<?php endif; ?>" >
                                        <?php echo $form['code_douane']->render(array('class' => 'form-control', 'placeholder' => 'Code douane')); ?>

                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-sm-12 " id="produit_code_comptable">
                                <div class="col-sm-12">
                                    <?php echo $form['code_comptable']->renderError() ?>
                                    <div class="form-group <?php if ($form['code_comptable']->hasError()): ?>has-error<?php endif; ?>" >
                                        <?php echo $form['code_comptable']->render(array('class' => 'form-control', 'placeholder' => 'Code comptable')); ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-sm-12 " id="produit_non_interpro">
                            <div class="col-sm-12">
                                <?php echo $form['produit_non_interpro']->renderError() ?>
                                <div class="form-group <?php if ($form['produit_non_interpro']->hasError()): ?>has-error<?php endif; ?>" >
                                    <?php echo $form['produit_non_interpro']->renderLabel(); ?>
                                    <?php echo $form['produit_non_interpro']->render(array('class' => 'form-control')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($form->getObject()->hasDepartements()): ?>
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><label>Départements</label></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 " id="produit_departements">
                                <span>Liste des départements :</span>

                                <div class="subForm contenu_onglet" id="formsDepartement">

                                    <?php foreach ($form['secteurs'] as $subform): ?>
                                        <?php include_partial('produit/subformDepartement', array('form' => $subform)) ?><br />
                                    <?php endforeach; ?>
                                    <a href="javascript:void(0)" class="btn_majeur btn_orange">Ajouter une ligne</a>
                                </div>
                                <input class="counteur" type="hidden" name="nb_departement" value="<?php echo count($form['secteurs']) ?>" />
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        <?php endif; ?> 
        <?php if ($form->getObject()->hasDroit(ConfigurationDroits::DROIT_DOUANE)): ?>
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><label>Droits circulation</label></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="formsDouane">
                                <?php foreach ($form['droit_douane'] as $subform): ?>
                                    <?php include_partial('produit/subformDroits', array('form' => $subform)) ?>
                                <?php endforeach; ?>
                                <a href="javascript:void(0)" class="btn_majeur btn_orange">Ajouter une ligne</a>
                            </div>
                            <input class="counteur" type="hidden" name="nb_douane" value="<?php echo count($form['droit_douane']) ?>" />
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($form->getObject()->hasDroit(ConfigurationDroits::DROIT_CVO)): ?>
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><label>Cotisations interprofessionnelles&nbsp;&nbsp;</label></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="formsCvo">
                                <?php foreach ($form['droit_cvo'] as $subform): ?>
                                    <?php include_partial('produit/subformDroits', array('form' => $subform)) ?>
                                <?php endforeach; ?>
                            </div>
                            <input class="counteur" type="hidden" name="nb_cvo" value="<?php echo count($form['droit_cvo']) ?>" />
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($form->getObject()->hasLabels()): ?>
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><label>Labels&nbsp;&nbsp;</label></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="formsLabel">
                                <?php foreach ($form['labels'] as $subform): ?>
                                    <?php include_partial('produit/subformLabel', array('form' => $subform)) ?>
                                <?php endforeach; ?>
                                <a href="javascript:void(0)" class="btn_majeur btn_orange"></a>
                            </div>
                            <input class="counteur" type="hidden" name="nb_label" value="<?php echo count($form['labels']) ?>" />
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($form->getObject()->hasDetails()): ?>
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><label>Activation des lignes&nbsp;&nbsp;</label></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="formsDetails">
                                <?php foreach ($form['detail'] as $detail): ?>
                                    <?php foreach ($detail as $type): ?>
                                        <div class="ligne_form">
                                            <?php if ($type['readable']->hasError()) { ?><span class="error"><?php echo $type['readable']->renderError() ?></span><?php } ?>				<?php echo $type['readable']->renderLabel() ?>
                                            <?php echo $type['readable']->render() ?>
                                            <?php echo $type['writable']->render() ?>
                                        </div>
                                        <br />
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
         <div class="col-sm-12">
            <a name="annuler" class="btn btn-default" href="<?php echo url_for('produits') ?>">Annuler</a>
            <button  name="valider" class="btn btn-success pull-right" type="submit">Valider</button>
        </div>
    </div>
</form>
<?php
include_partial('templateformsDepartement');
include_partial('templateformsDouane');
include_partial('templateformsCvo');
include_partial('templateformsLabel');
?>