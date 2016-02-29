<?php
echo $societeForm->renderHiddenFields();
echo $societeForm->renderGlobalErrors();
?>
<div class="panel-body">
    <div class="form-group">
                <label class="col-xs-4 control-label" for="type_societe">Type de la société</label>
                <span class="col-xs-8 text-left"><?php echo $societeForm->getObject()->type_societe; ?></span>
    </div>
    <div class="form-group<?php if($societeForm['raison_sociale']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $societeForm['raison_sociale']->renderError(); ?>
        <?php echo $societeForm['raison_sociale']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $societeForm['raison_sociale']->render(); ?></div>
    </div>
    <div class="form-group<?php if($societeForm['raison_sociale_abregee']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $societeForm['raison_sociale_abregee']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
			<div class="col-xs-8"><?php echo $societeForm['raison_sociale_abregee']->render(); ?></div>
			<?php echo $societeForm['raison_sociale_abregee']->renderError(); ?>
    </div>
    <div class="form-group<?php if($societeForm['statut']->hasError()): ?> has-error<?php endif; ?>">
			<?php echo $societeForm['statut']->renderError(); ?>
            <?php echo $societeForm['statut']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
			<div class="col-xs-8"><?php echo $societeForm['statut']->render(); ?></div>
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
         <div class="form-group<?php if($societeForm['type_fournisseur']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $societeForm['type_fournisseur']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
            <div class="col-xs-8"><?php echo $societeForm['type_fournisseur']->render(); ?></div>
            <?php echo $societeForm['type_fournisseur']->renderError(); ?>
             </div>          
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
    
        <div id="enseignes_list">
            <?php
            foreach ($societeForm['enseignes'] as $enseigneForm) {
                include_partial('itemEnseigne', array('form' => $enseigneForm));
            }
            ?>
<!--            <div class="form-group">
                <a class="btn_ajouter_ligne_template" data-container="#enseignes_list" data-template="#template_enseigne" href="#">Ajouter une enseigne</a>
            </div>-->
        </div>
    <div class="form-group<?php if($societeForm['commentaire']->hasError()): ?> has-error<?php endif; ?>">
<?php echo $societeForm['commentaire']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $societeForm['commentaire']->render(); ?></div>
        <?php echo $societeForm['commentaire']->renderError(); ?>
    </div>
</div>
<?php // include_partial('templateEnseigneItem', array('form' => $societeForm->getFormTemplate()));
?>

