<?php use_helper('Float'); ?>
<?php use_helper('Date'); ?>
<?php use_javascript('facture.js'); ?>

<div class="col-xs-12">
    <h2>Edition de factures libres</h2>

    <form id="form_mouvement_edition_facture" action="" method="post" class="form-horizontal">


        <?php echo $form->renderGlobalErrors(); ?>       



        <?php if ($form->hasErrors()): ?>
            <div class="alert alert-danger" role="alert">
                Veuillez compléter ou corriger les erreurs
            </div>
        <?php endif; ?>
        <div class="row row-margin"  style="border-bottom: 1px dotted #d2d2d2; padding-bottom: 20px;">
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-xs-12"><?php echo $form['libelle']->renderError(); ?>  </div>
                    <div class="col-xs-12"><?php echo $form['libelle']->renderLabel(); ?>  </div>
                    <div class="col-xs-12"><?php echo $form['libelle']->render(array('class' => 'form-control input-lg text-right')); ?>  </div>
                </div>


            </div>
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <?php if ($form->getObject()->getDate()): ?>
                            <span >Facture Libre du <?php echo format_date($form->getObject()->getDate(), "dd/MM/yyyy", "fr_FR") ?></span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        <br/>

        <div class="row row-margin">
            <div class="col-xs-12" style="border-bottom: 1px dotted #d2d2d2;" id="mouvementsfacture_list"  data-template="#template_mouvementfacture">
                <div class="row">
                    <div class="col-xs-3 text-center lead text-muted">Identité</div>
                    <div class="col-xs-4 text-center lead text-muted">Code comptable</div>
                    <div class="col-xs-2 text-center lead text-muted">Complément de libellé</div>
                    <div class="col-xs-1 text-center lead text-muted">Prix&nbsp;U.</div>
                    <div class="col-xs-1 text-center lead text-muted">Quantité</div>
                    <div class="col-xs-1 text-center lead text-muted">&nbsp;</div>
                </div>
                <?php foreach ($form['mouvements'] as $key => $mvtForm): ?>
                    <?php include_partial('itemMouvementFacture', array('mvtForm' => $mvtForm, 'item' => $factureMouvements->mouvements->get(str_replace('_', '/', $key)))); ?>

                <?php endforeach; ?> 
                <?php include_partial('templateMouvementFactureItem', array('mvtForm' => $form->getFormTemplate(), 'mvtKey' => $form->getNewMvtId())); ?>
            </div>
            <?php echo $form->renderHiddenFields(); ?>
        </div>
        <br/>
        <div class="row row-margin">
            <div class="col-xs-6 text-left">
                <a class="btn btn-danger btn-lg btn-upper" href="<?php echo url_for('facture_mouvements') ?>">Annuler</a>
            </div>
            <div class="col-xs-6 text-right">
                <input type="button" class="btn btn-success btn-lg btn-upper" value="Valider" onclick="this.form.submit();" />
            </div>
        </div>

    </form>
</div>
