<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>

</ol>

<section id="principal">

  <div class="row">
      <div class="col-xs-12">
          <?php include_component('subvention', 'formEtablissementChoice') ?>
      </div>
  </div>

    <div class="row">
      <div class="col-xs-12">
        <table id="table_contrats" class="table">
          <thead>
            <tr>
              <th>Société</th>
              <th>Subvention</th>
              <th style="width: 110px;">Date création</th>
              <th>Statut</th>
              <th>Documents</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($subventions as $subventionNom => $subventionsDate): ?>
              <?php foreach ($subventionsDate as $date => $subvention): ?>
                <tr>
                  <td class="">
                    <span><?php echo $subvention->declarant->raison_sociale; ?></span>
                  </td>
                  <td class="">
                    <span><?php echo $subvention->operation; ?></span>
                  </td>
                  <td class="text-center">
                    <span><?php echo DateTime::createFromFormat('Y-m-d H:i:s',$date)->format("d/m/Y"); ?></span>
                  </td>
                  <td class="text-center">
                    <span class="label
                    <?php if($subvention && $subvention->isApprouve()): ?>
                    label-success
                    <?php elseif($subvention && ($subvention->isApprouvePartiellement() || $subvention->isRefuse())): ?>
                    label-danger
                    <?php elseif($subvention && $subvention->isValide()): ?>
                    label-warning
                    <?php else: ?>
                    label-default
                    <?php endif; ?>"
                    >
                    <?php echo $subvention->getStatutLibelle(); ?></span>
                  </td>
                  <td class="text-center">
                    <?php if($subvention->statut): ?>
                    <div class="btn-group" role="group">
                      <a href="<?php echo url_for('subvention_zip', $subvention) ?>" class="btn btn-default"><span class="glyphicon glyphicon-save-file"></span>&nbsp;</a>
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                      <ul class="dropdown-menu">
                        <li><a href="<?php echo url_for('subvention_pdf', $subvention) ?>">Fiche de pré-qualification</a></li>
                                <li><a href="<?php echo url_for('subvention_xls', $subvention) ?>">Descriptif détaillé de l'opération</a></li>
                                <li><a href="">Notice</a></li>
                                <li><a href="">Charte graphique</a></li>
                              </ul>
                    </div>
                  <?php endif; ?>
                  </td>
                  <td class="text-center">
                  <?php if($subvention && $subvention->isApprouve()): ?>
                    <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $subvention->identifiant, 'operation' => $subventionNom)); ?>" class="btn btn-default col-xs-12"><span class="glyphicon glyphicon-save-file"></span> Gérer le dossier</a>
                  <?php elseif($subvention && ($subvention->isApprouvePartiellement() || $subvention->isRefuse())): ?>
                    <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $subvention->identifiant, 'operation' => $subventionNom)); ?>" class="btn btn-default col-xs-12"><span class="glyphicon glyphicon-save-file"></span> Gérer le dossier</a>
                  <?php elseif($subvention && $subvention->isValide()): ?>
                    <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $subvention->identifiant, 'operation' => $subventionNom)); ?>" class="btn btn-default col-xs-12"><span class="glyphicon glyphicon-save-file"></span> Gérer le dossier</a>
                  <?php else: ?>
                    <a href="<?php echo url_for('subvention_infos',array('identifiant' => $subvention->identifiant, 'operation' => $subventionNom)); ?>" class="btn btn-default col-xs-12"><span class="glyphicon glyphicon-save-file"></span> Continuer la saisie</a>
                  <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach ?>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
</section>
