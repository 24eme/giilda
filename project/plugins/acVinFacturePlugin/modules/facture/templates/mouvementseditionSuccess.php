<?php use_helper('Float'); ?>
<?php use_javascript('facture.js'); ?>

<div class="col-xs-12">
    <h2>Mouvements de facture</h2>
    
    <form id="form_mouvement_edition_facture" action="" method="post" class="form-horizontal">

        <?php echo $form->renderHiddenFields(); ?>
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
                    <div class="col-xs-12"><?php echo $form['date']->renderError(); ?>  </div>
                    <div class="col-xs-12"><?php echo $form['date']->renderLabel(); ?>  </div>
                    <div class="col-xs-12"><?php echo $form['date']->render(array('class' => 'form-control input-lg text-right')); ?>  </div>
                </div>

            </div>
        </div>
        <br/>

        <div class="row row-margin">
            <div class="col-xs-12" style="border-bottom: 1px dotted #d2d2d2;" id="mouvementsfacture_list"  data-template="#template_mouvementfacture">
                <div class="row">
                    <div class="col-xs-3 text-center lead text-muted">Identité</div>
                    <div class="col-xs-3 text-center lead text-muted">Code comptable</div>
                    <div class="col-xs-3 text-center lead text-muted">Complément de libellé</div>
                    <div class="col-xs-1 text-center lead text-muted">Quantité</div>
                    <div class="col-xs-1 text-center lead text-muted">Prix&nbsp;U.</div>
                    <div class="col-xs-1 text-center lead text-muted">&nbsp;</div>
                </div>
                <?php foreach ($form['mouvements'] as $key => $mvtForm): ?>
                        <?php include_partial('itemMouvementFacture', array('mvtForm' => $mvtForm)); ?>
                   
                <?php endforeach; ?> 
                <?php include_partial('templateMouvementFactureItem', array('mvtForm' => $form->getFormTemplate(), 'mvtKey' => $form->getNewMvtId())); ?>
            </div>

        </div>
        <br/>
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
