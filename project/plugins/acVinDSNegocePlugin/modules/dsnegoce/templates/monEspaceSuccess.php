<?php use_helper('Date'); ?>
<?php include_partial('dsnegoce/preTemplate'); ?>
<?php include_partial('dsnegoce/breadcrum', array('etablissement' => $etablissement)); ?>

<div class="row">
    <div class="col-xs-12 formEtablissement">
        <?php include_component('dsnegoce', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>
</div>

<h1>Déclaration de Stock</h1>

<div class="col-xs-12">
    <div class="row">
      <h4>
          <form class="form-inline pull-right" method="get">
              <?php echo $formPeriodes->renderGlobalErrors() ?>
              <?php echo $formPeriodes->renderHiddenFields() ?>
              Stock à fin :
              <div class="form-group<?php if($formPeriodes['periode']->hasError()): ?> has-error<?php endif; ?>">
                  <?php echo $formPeriodes['periode']->renderError(); ?>
                  <?php echo $formPeriodes['periode']->render(); ?>
              </div>
              <button type="submit" class="btn btn-default">Changer</button>
          </form>
        </h4>
    </div>

    <p>&nbsp;</p>

    <div class="row">
      <?php if($dsnegoce->isNew()): ?>
        N'existe pas
      <?php else: ?>
        Exist
      <?php endif; ?>
    </div>

</div>

<?php include_partial('dae/postTemplate'); ?>
