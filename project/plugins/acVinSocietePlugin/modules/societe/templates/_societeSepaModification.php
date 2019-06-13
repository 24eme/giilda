<div class="form_contenu">
  <div class="form_ligne">
      <?php echo $societeForm['nom_bancaire']->renderLabel(); ?>
      <?php echo $societeForm['nom_bancaire']->render(array('class' => 'champ_long')); ?>
      <?php echo $societeForm['nom_bancaire']->renderError(); ?>
  </div>

  <div class="form_ligne">
      <?php echo $societeForm['iban']->renderLabel(); ?>
      <?php echo $societeForm['iban']->render(array('class' => 'champ_long')); ?>
      <?php echo $societeForm['iban']->renderError(); ?>
  </div>

  <div class="form_ligne">
      <?php echo $societeForm['bic']->renderLabel(); ?>
      <?php echo $societeForm['bic']->render(); ?>
      <?php echo $societeForm['bic']->renderError(); ?>
  </div>
</div>
