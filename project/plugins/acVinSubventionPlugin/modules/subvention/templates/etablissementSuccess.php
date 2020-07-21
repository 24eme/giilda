<ol class="breadcrumb">
    <li class="active"><a href="<?php echo "#" ?>">Subvention</a></li>
</ol>
<div class="row row-margin">
    <div class="col-xs-8">
        <h2 class="vertical-center" style="margin: 0 0 20px 0;">Demande de subvention</h2>
    </div>
    <div class="col-xs-4 text-right">
<a href="<?php echo url_for('subvention_creation',array('identifiant' => $etablissement->identifiant, 'operation' => "COVID1")); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Nouvelle Subvention</a>
    </div>
</div>


  <div class="row row-margin">
    <table id="table_contrats" class="table">
    <thead>
        <tr>
            <th>Type</th>
            <th style="width: 110px;">Date</th>
            <th>Etape</th>
            <th>Documents</th>
            <th>Pdf</th>
            <th>Action</th>
          </tr>
    </thead>
    <tbody>
      <?php foreach ($subventions as $subventionNom => $subventionsDate): ?>
        <?php foreach ($subventionsDate as $date => $subvention): ?>
          <tr>
            <td class="text-center">
              <span><?php echo $subventionNom; ?></span>
            </td>
            <td class="text-center">
              <span><?php echo $date; ?></span>
            </td>
            <td class="text-center">
              <span><?php echo $subventionNom; ?></span>
            </td>
            <td class="text-center">
              <span><?php echo $subventionNom; ?></span>
            </td>
            <td class="text-center">
              <span><?php echo $subventionNom; ?></span>
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
