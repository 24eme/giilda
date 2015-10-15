<?php use_helper('Float'); use_helper('Vrac'); ?>

<?php include_component('vrac', 'etapes', array('vrac' => $vrac, 'compte' => $compte, 'actif' => 4, 'urlsoussigne' => null, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<?php include_partial('document_validation/validation', array('validation' => $validation)); ?>

<form action="" method="post" class="form-horizontal" id="contrat_validation" >
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>

<?php include_partial("vrac/recap", array('vrac' => $vrac, 'isTeledeclarationMode' => $isTeledeclarationMode, 'template_validation' => 1)); ?>

<?php if ($validation->isValide()) : ?>
<div class="row">

                <?php if (isset($form['date_signature'])): ?>
                    <?php echo $form['date_signature']->renderError(); ?>
                    <div class="form-group">
                        <?php echo $form['date_signature']->renderError(); ?>
		                <?php echo $form['date_signature']->renderLabel("Date de signature :", array('class' => 'col-sm-8 control-label')); ?>
		                <div class="col-sm-4">
		                    <?php echo $form['date_signature']->render(); ?>
		                </div>
                    </div>
                 <?php endif; ?>

</div>
<?php endif; ?>        

<div class="row">
    <div class="col-xs-4 text-left">
        <a tabindex="-1" href="<?php echo url_for('vrac_marche', $vrac); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Etape précédente</a>
    </div>
    <div class="col-xs-4 text-center">
        <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
            <a tabindex="-1" class="btn btn-danger" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>" style="margin-left: 10px">Supprimer le brouillon
            </a>
        <?php endif; ?>
    </div>
    <div class="col-xs-4 text-right">
        <?php if ($validation->isValide()) : ?>
            <?php if ($isTeledeclarationMode): ?>
                <?php if ($signatureDemande): ?>
                    <a href="#signature_popup_content" class="btn btn-default">Signer le contrat</a> 
                    <?php include_partial('signature_popup', array('vrac' => $vrac, 'societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal, 'validation' => true)); ?>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn btn-success" type="submit">Terminer la saisie <span class="glyphicon glyphicon-ok"></span></button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

</form>