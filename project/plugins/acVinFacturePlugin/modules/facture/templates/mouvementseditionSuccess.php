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
            <div class="col-xs-12" style="border-bottom: 1px dotted #d2d2d2;" id="mouvementsfacture_list">
                <div class="row">
                    <div class="col-xs-3">Identité</div>
                    <div class="col-xs-2 text-center lead text-muted">Code comptable</div>
                    <div class="col-xs-3 text-center lead text-muted">Libellé</div>
                    <div class="col-xs-1 text-center lead text-muted">Quantité</div>

                    <div class="col-xs-1 text-center lead text-muted">Prix&nbsp;U.</div>
                </div>

                <?php foreach ($form['mouvements'] as $key_etb => $etbMvts): ?>
                    <?php foreach ($etbMvts as $key_mvt => $mvt): ?>
                        <?php include_partial('itemMouvementFacture', array('mvt' => $mvt)); ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php
                include_partial('templateMouvementFactureItem', array('mvt' => $form->getFormTemplate())); ?>
            </div>
                <div class="ajouter_mouvement_facture">
                    <a class="btn_ajouter_ligne_template btn_majeur" data-container="#mouvementsfacture_list" data-template="#template_mouvementfacture" href="#">Ajouter un non apurement</a>
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
