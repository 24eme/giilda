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
              <th style="width: 110px;">Date création</th>
              <th>Etape</th>
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
                  <td class="text-center">
                    <span><?php echo (DateTime::createFromFormat('Y-m-d H:i:s',$date))->format("d/m/Y"); ?></span>
                  </td>
                  <td class="text-center">
                    <span><?php echo "ll"; ?></span>
                  </td>
                  <td class="text-center">
                    <span><?php echo ""; ?></span>
                  </td>
                  <td class="text-center">
                    <a href="<?php echo url_for('subvention_infos',array('identifiant' => $subvention->identifiant, 'operation' => $subventionNom)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Continuer la Subvention</a>
                  </td>
                </tr>
              <?php endforeach ?>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
</section>
