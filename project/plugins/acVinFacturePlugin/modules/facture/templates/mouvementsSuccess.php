<?php use_helper('Float'); ?>
<?php use_javascript('facture.js'); ?>

<div class="col-xs-12">
    <h2>Mouvements de facture</h2>

    <form id="form_mouvement_edition_facture" action="" method="post" class="form-horizontal">

        <?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>

        <?php if ($form->hasErrors()): ?>
            <div class="alert alert-danger" role="alert">
                Veuuillez compléter ou corriger les erreurs
            </div>
        <?php endif; ?>
        <div class="row row-margin"  style="border-bottom: 1px dotted #d2d2d2; padding-bottom: 20px;">
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-xs-12"><?php echo $form['libelle']->renderLabel(); ?>  </div>
                    <div class="col-xs-12"><?php echo $form['libelle']->render(array('class' => 'form-control input-lg text-right')); ?>  </div>
                </div>


            </div>
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-xs-12"><?php echo $form['date']->renderLabel(); ?>  </div>
                    <div class="col-xs-12"><?php echo $form['date']->render(array('class' => 'form-control input-lg text-right')); ?>  </div>
                </div>

            </div>
        </div>
        <br/>

        <div class="row row-margin">
            <div class="col-xs-12" style="border-bottom: 1px dotted #d2d2d2;">
                <div class="row">
                    <div class="col-xs-3">Identité</div>
                    <div class="col-xs-2 text-center lead text-muted">Code comptable</div>
                    <div class="col-xs-3 text-center lead text-muted">Libellé</div>
                    <div class="col-xs-1 text-center lead text-muted">Quantité</div>

                    <div class="col-xs-1 text-center lead text-muted">Prix&nbsp;U.</div>
                </div>
                <?php foreach ($form['mouvements'] as $key_ligne => $f_ligne): ?>

                    <div class="form-group line ">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-3">
                                    <?php echo $f_ligne['identifiant']->render(array('class' => 'form-control select2autocomplete input-md', 'placeholder' => 'Rechercher')); ?>
                                </div>
                                <div class="col-xs-2">

                                    <?php echo $f_ligne['identifiant_analytique']->render(array('class' => 'form-control input-lg text-right')); ?> 
                                </div>
                                <div class="col-xs-3">

                                    <?php echo $f_ligne['libelle']->render(array('class' => 'form-control input-lg text-right')); ?>  
                                </div>
                                <div class="col-xs-1">

                                    <?php echo $f_ligne['quantite']->render(array('class' => 'form-control input-lg text-right')); ?>  
                                </div>
                                <div class="col-xs-1">

                                    <?php echo $f_ligne['prix_unitaire']->render(array('class' => 'form-control input-lg text-right')); ?>  
                                </div>

                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row row-margin">
            <div class="col-xs-6 text-left">
                <a class="btn btn-danger btn-lg btn-upper" href="<?php echo url_for('facture_mouvements') ?>">Annuler</a>
            </div>
            <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-success btn-lg btn-upper">Valider</button>
            </div>
        </div>

    </form>
</div>