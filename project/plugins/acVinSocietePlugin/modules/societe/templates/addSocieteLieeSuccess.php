<h3>Ajout d'une société liée</h3>

<p>
Sociétés liées actuellement :
<?php if (count($societe->societes_liees) === 0): ?>
  <em>Aucune société liée</em>
<?php else: ?>
  <?php foreach ($societe->societes_liees as $sid): ?>
    <?php $societeLiee = SocieteClient::getInstance()->find($sid); ?>
    <a href="<?php echo url_for('societe_visualisation', ['identifiant' => $sid]) ?>">
      <?php echo $societeLiee->raison_sociale . " ($sid)" ?>
    </a>
  <?php endforeach ?>
<?php endif ?>
</p>

<form id="add-societe-liee" method="POST">
  <?php echo $form->renderHiddenFields() ?>
  <?php echo $form->renderGlobalErrors() ?>
  <?php echo $form['societe-liee']->renderError(); ?>
  <div class="col-xs-10">
    <div class="form-group<?php if($form['societe-liee']->hasError()): ?> has-error<?php endif; ?>">
        <?php echo $form['societe-liee']->render(array('class' => 'form-control select2autocomplete input-md', 'placeholder' => 'Rechercher')); ?>
    </div>
  </div>
  <div class="col-xs-2">
    <button class="btn btn-default btn-md" type="submit" id="btn_rechercher">Ajouter</button>
  </div>
</form>
