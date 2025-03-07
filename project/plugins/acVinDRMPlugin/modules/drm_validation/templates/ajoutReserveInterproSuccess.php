<?php include_partial('drm/breadcrumb', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<section id="principal" class="drm">

<h3>Ajout de produit dans la réserve interprofessionnel</h3>
<form action="<?php echo url_for('drm_ajout_recolte_etablissement', ['identifiant' => $drm->identifiant, 'periode_version' => $drm->periode]) ?>" method="POST">
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
      <tr>
        <td>
          <?php echo $form['hashref']->render(array("placeholder" => "Ajouter un produit")); ?>
        </td>
        <td>
            <div class="input-group">
              <?php echo $form['volume']->render() ?>
              <span class="input-group-addon" style="background: #f2f2f2;"><small class="text-muted">hl</small></span>
            </div>
        </td>
      </tr>
    </tbody>
  </table>

  <div class="text-right">
    <button type="submit" class="btn btn-success">Valider <span class="glyphicon glyphicon-chevron-right"></span></button>
  </div>

</form>
</section>
