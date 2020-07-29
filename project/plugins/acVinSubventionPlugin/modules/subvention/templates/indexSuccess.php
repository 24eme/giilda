<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>

</ol>

<section id="principal">

  <div class="row" id="formEtablissementChoice">
      <div class="col-xs-12">
          <?php include_component('subvention', 'formEtablissementChoice') ?>
      </div>
  </div>

    <div class="row">
      <div class="col-xs-12">
        <table id="table_contrats" class="table table-bordered">
          <thead>
            <tr>
              <th>Société</th>
              <th>Subvention</th>
              <th style="width: 110px;">Date création</th>
              <th class="text-center">Statut</th>
              <th class="text-center">Documents</th>
              <th class="text-center">Action</th>
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
                    <span><?php echo ($date)? DateTime::createFromFormat('Y-m-d H:i:s', $date)->format("d/m/Y") : ''; ?></span>
                  </td>
                  <td class="text-center">
                    <span class="label
                    <?php if($subvention && $subvention->isApprouve()): ?>
                    label-success
                    <?php elseif($subvention && $subvention->isRefuse()): ?>
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
                      <button type="button" class="btn btn-link btn-xs btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Télécharger <span class="caret"></span></button>
                      <ul class="dropdown-menu">
                        <li><a href="<?php echo url_for('subvention_pdf', $subvention) ?>">Fiche de pré-qualification (PDF)</a></li>
                        <li><a href="<?php echo url_for('subvention_xls', $subvention) ?>">Descriptif détaillé de l'opération (Excel)</a></li>
                      </ul>
                    </div>
                  <?php endif; ?>
                  </td>
                  <td>
                    <a href="<?php echo url_for('subvention_etablissement',array('identifiant' => $subvention->identifiant)); ?>" class="btn btn-xs btn-default btn-block">Voir le dossier</a>
                  </td>
                </tr>
              <?php endforeach ?>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
</section>
