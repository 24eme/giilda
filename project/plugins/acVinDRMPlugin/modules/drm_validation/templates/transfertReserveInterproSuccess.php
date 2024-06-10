<?php include_partial('drm/breadcrumb', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<section id="principal" class="drm">

<h3>Modification de la réserve interprofessionnel</h3>

<form action="<?php echo url_for('drm_transfert_recolte_etablissement', ['identifiant' => $drm->identifiant, 'periode_version' => $drm->periode]) ?>" method="POST">
  <?php echo $form->renderGlobalErrors(); ?>
  <?php echo $form->renderHiddenFields(); ?>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th class="col-xs-8">Produit</th>
        <th class="col-xs-4">Volume <small class="text-muted">(hl)</small></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($form->getProduits() as $hash): ?>
        <tr class="vertical-center">
          <td><?php echo $form[$hash]->renderLabel() ?></td>
          <td>
              <div class="input-group">
                <?php echo $form[$hash]->render() ?>
                <span class="input-group-addon" style="background: #f2f2f2;"><small class="text-muted">hl</small></span>
              </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="text-right">
    <button type="submit" class="btn btn-success">Valider <span class="glyphicon glyphicon-chevron-right"></span></button>
  </div>

</form>
</section>
