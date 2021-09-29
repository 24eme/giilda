<?php use_helper('Float'); ?>
<?php use_helper('Date'); ?>
<?php use_javascript('facture.js'); ?>
<?php include_partial('facture/preTemplate'); ?>

<ol class="breadcrumb">
    <li class="visited"><a href="<?php echo url_for('facture') ?>">Factures</a></li>
    <li class="visited"><a href="<?php echo url_for('facture_mouvements') ?>">Facturation libre</a></li>
    <li class="active"><a href="<?php echo url_for('facture_mouvements_edition', array('id' => $form->getObject()->_id)) ?>" class="active">Facture libre n°&nbsp;<?php echo $form->getObject()->identifiant; ?></a></li>
</ol>

<h2>Facturation libre n°&nbsp;<?php echo $form->getObject()->identifiant; ?><?php if ($form->getObject()->getDate()): ?> du <?php echo format_date($form->getObject()->getDate(), "dd/MM/yyyy", "fr_FR") ?><?php endif; ?></h2>

<form id="form_mouvement_edition_facture" action="" method="post" class="form-horizontal">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>

    <div class="row row-margin">
        <div class="col-xs-12">
            <div class="row">
              <div class="col-xs-8 col-xs-offset-4"><?php echo $form['libelle']->renderError(); ?></div>
              <div class="col-xs-4 h4 text-muted text-right"><?php echo $form['libelle']->renderLabel(); ?></div>
              <div class="col-xs-8"><?php echo $form['libelle']->render(array('class' => 'form-control')); ?>  </div>
            </div>
        </div>
    </div>
    <hr />

    <div class="row row-margin">
        <div class="col-xs-12" style="border-bottom: 1px dotted #d2d2d2;" id="mouvementsfacture_list">
            <div class="row">
                <div class="col-xs-4 text-center h4 text-muted">Société</div>
                <div class="col-xs-2 text-center h4 text-muted">Lien comptable</div>
                <div class="col-xs-3 text-center h4 text-muted">Libellé article</div>
                <div class="col-xs-2 text-center h4 text-muted">Prix&nbsp;U.</div>
                <div class="col-xs-1 text-center h4 text-muted" style="padding-left: 0;">Quantité</div>
            </div>
            <?php
            foreach ($form['mouvements'] as $k => $mvtsForm) {
              foreach ($mvtsForm as $sk => $mvtForm) {
                $object = ($k != 'nouveau')? $form->getObject()->mouvements->get($k)->get($sk) : null;
                include_partial('itemMouvementFacture', array('mvtForm' => $mvtForm, 'object' => $object));
              }
            }
            ?>
        </div>
    </div>
    <br/>
    <div class="row row-margin">
        <div class="col-xs-6 text-left">
            <a class="btn btn-default btn-lg btn-upper" tabindex="-1" href="<?php echo url_for('facture_mouvements') ?>">Retour</a>
        </div>
        <div class="col-xs-6 text-right">
            <input type="button" class="btn btn-success btn-lg btn-upper" value="Valider" onclick="this.form.submit();" />
        </div>
    </div>

</form>
<?php include_partial('facture/postTemplate'); ?>
