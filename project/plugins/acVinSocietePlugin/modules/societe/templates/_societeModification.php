<?php
echo $societeForm->renderHiddenFields();
echo $societeForm->renderGlobalErrors();
?>
<div class="panel-body">
    <div class="form-group<?php if ($societeForm['type_societe']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $societeForm['type_societe']->renderError(); ?>
        <?php echo $societeForm['type_societe']->renderLabel("Type de la société", array('class' => 'col-xs-4 control-label')); ?>
        <div class="col-xs-8"><?php echo $societeForm['type_societe']->render(array("autofocus" => "autofocus")); ?></div>
    </div>
    <div class="form-group<?php if($societeForm['raison_sociale']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $societeForm['raison_sociale']->renderError(); ?>
        <?php echo $societeForm['raison_sociale']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $societeForm['raison_sociale']->render(); ?></div>
    </div>
    <div class="form-group<?php if($societeForm['code_comptable_client']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $societeForm['code_comptable_client']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
			<div class="col-xs-8"><?php echo $societeForm['code_comptable_client']->render(array('placeholder' => "Automatique")); ?></div>
			<?php echo $societeForm['code_comptable_client']->renderError(); ?>
    </div>
    <?php if ($societeForm->getObject()->isNegoOrViti()) : ?>
        <div class="form-group<?php if($societeForm['cooperative']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $societeForm['cooperative']->renderError(); ?>
            <?php echo $societeForm['cooperative']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
            <div class="col-xs-8"><?php echo $societeForm['cooperative']->render(); ?></div>
        </div>
    <?php endif; ?>
    <?php if (isset($societeForm['type_numero_compte_fournisseur']) || isset($societeForm['type_numero_compte_client'])): ?>
    <div class="form-group<?php if($societeForm['type_numero_compte_client']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $societeForm['type_numero_compte_client']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
            <?php if ($societeForm->getObject()->isOperateur()) : ?>
                <?php echo $societeForm['type_numero_compte_client']->renderError(); ?>
                <div class="col-xs-8"><?php echo $societeForm['type_numero_compte_client']->render(); ?></div>
            <?php endif; ?>
            <?php if (isset($societeForm['type_numero_compte_fournisseur'])): ?>
            <?php echo $societeForm['type_numero_compte_fournisseur']->render(); ?>
            <?php echo $societeForm['type_numero_compte_fournisseur']->renderError(); ?>
            <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="form-group<?php if($societeForm['siret']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $societeForm['siret']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
		<div class="col-xs-8"><?php echo $societeForm['siret']->render(); ?></div>
		<?php echo $societeForm['siret']->renderError(); ?>
	</div>
	<div class="form-group<?php if($societeForm['code_naf']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $societeForm['code_naf']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
		<div class="col-xs-8"><?php echo $societeForm['code_naf']->render(); ?></div>
		<?php echo $societeForm['code_naf']->renderError(); ?>
    </div>
    <div class="form-group<?php if($societeForm['no_tva_intracommunautaire']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $societeForm['no_tva_intracommunautaire']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $societeForm['no_tva_intracommunautaire']->render(); ?></div>
        <?php echo $societeForm['no_tva_intracommunautaire']->renderError(); ?>
    </div>
    <div class="form-group<?php if($societeForm['commentaire']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $societeForm['commentaire']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $societeForm['commentaire']->render(); ?></div>
        <?php echo $societeForm['commentaire']->renderError(); ?>
    </div>
</div>
<?php // include_partial('templateEnseigneItem', array('form' => $societeForm->getFormTemplate()));
?>
