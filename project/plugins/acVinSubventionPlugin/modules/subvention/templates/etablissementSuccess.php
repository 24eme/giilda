<ol class="breadcrumb">
  <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
  <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->siret ?>)</a></li>

</ol>

<section id="principal">

  <div class="row">
    <div class="col-xs-12">
      <?php include_component('subvention', 'formEtablissementChoice') ?>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <h2>Demande des subventions</h2>

      <p>Espace des demandes de subventions auprès de votre interprofession</p>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><?php echo $etablissement->raison_sociale ?></h3></div>
        <div class="panel-body">
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-4 control-label">Famille</label>
            <div class="col-sm-8">
              <p class="form-control-static"><?php echo EtablissementFamilles::getFamilleLibelle($etablissement->famille) ?></p>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-4 control-label">SIRET</label>
            <div class="col-sm-8">
              <p class="form-control-static"><?php echo $etablissement->siret ?></p>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-4 control-label">Adresse</label>
            <div class="col-sm-8">
              <p class="form-control-static"><?php echo $etablissement->adresse ?></p>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-4 control-label">Code postal</label>
            <div class="col-sm-8">
              <p class="form-control-static"><?php echo $etablissement->code_postal ?></p>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-4 control-label">Commune</label>
            <div class="col-sm-8">
              <p class="form-control-static"><?php echo $etablissement->commune ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Subvention en cours : </h3></div>
        <div class="panel-body">
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-10 col-sm-offset-1">Aucune subvention <?php echo $operation_en_cours; ?> encore commencée</label>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <div class="col-sm-10 col-sm-offset-1">
              <a href="<?php echo url_for('subvention_creation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Démarrer la subvention <?php echo $operation_en_cours; ?> </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
