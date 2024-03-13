<div class="panel-body">
    <?php
    echo $etablissementForm->renderHiddenFields();
    echo $etablissementForm->renderGlobalErrors();
    ?>

    <?php if(isset($etablissementForm['identifiant'])): ?>
    <div class="form-group<?php if($etablissementForm['identifiant']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['identifiant']->renderError(); ?>
        <?php echo $etablissementForm['identifiant']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['identifiant']->render(); ?></div>
    </div>
    <?php else: ?>
        <div class="form-group">
            <label class="col-xs-4 control-label">Identifiant</label>
            <div class="col-xs-8">
              <p class="form-control-static"><?php echo $etablissementForm->getObject()->identifiant ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group<?php if($etablissementForm['famille']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['famille']->renderError(); ?>
        <?php echo $etablissementForm['famille']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['famille']->render(); ?></div>
    </div>

    <div class="form-group<?php if($etablissementForm['nom']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['nom']->renderError(); ?>
        <?php echo $etablissementForm['nom']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['nom']->render(); ?></div>
    </div>

    <div class="form-group<?php if($etablissementForm['nature_inao']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['nature_inao']->renderError(); ?>
        <?php echo $etablissementForm['nature_inao']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['nature_inao']->render(); ?></div>
    </div>
    <?php if(isset($etablissementForm['region'])): ?>
      <div class="form-group<?php if($etablissementForm['region']->hasError()): ?> has-error<?php endif; ?>">
              <?php echo $etablissementForm['region']->renderError(); ?>
              <?php echo $etablissementForm['region']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
              <div class="col-xs-8"><?php echo $etablissementForm['region']->render(); ?></div>
      </div>
    <?php endif; ?>
    <?php if (!$etablissement->isCourtier()): ?>
        <div class="form-group<?php if($etablissementForm['cvi']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $etablissementForm['cvi']->renderError(); ?>
            <?php echo $etablissementForm['cvi']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
            <div class="col-xs-8"><?php echo $etablissementForm['cvi']->render(); ?></div>
        </div>
        <div class="form-group<?php if($etablissementForm['ppm']->hasError()): ?> has-error<?php endif; ?>">
            <?php echo $etablissementForm['ppm']->renderError(); ?>
            <?php echo $etablissementForm['ppm']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
            <div class="col-xs-8"><?php echo $etablissementForm['ppm']->render(); ?></div>
        </div>
    <?php endif; ?>
     <?php if ($etablissement->isCourtier()): ?>
    <div class="form-group<?php if($etablissementForm['carte_pro']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['carte_pro']->renderError(); ?>
        <?php echo $etablissementForm['carte_pro']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['carte_pro']->render(); ?></div>
    </div>
    <?php endif; ?>
    <div class="form-group<?php if($etablissementForm['no_accises']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['no_accises']->renderError(); ?>
        <?php echo $etablissementForm['no_accises']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['no_accises']->render(); ?></div>
    </div>
    <div class="form-group<?php if($etablissementForm['num_interne']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['num_interne']->renderError(); ?>
        <?php echo $etablissementForm['num_interne']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['num_interne']->render(); ?></div>
    </div>
    <div class="form-group<?php if($etablissementForm['mois_stock_debut']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['mois_stock_debut']->renderError(); ?>
        <?php echo $etablissementForm['mois_stock_debut']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['mois_stock_debut']->render(); ?></div>
    </div>
    <div class="form-group<?php if($etablissementForm['commentaire']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $etablissementForm['commentaire']->renderError(); ?>
        <?php echo $etablissementForm['commentaire']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
        <div class="col-xs-8"><?php echo $etablissementForm['commentaire']->render(); ?></div>
    </div>

</div>
