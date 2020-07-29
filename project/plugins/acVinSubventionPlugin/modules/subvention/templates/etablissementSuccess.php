<ol class="breadcrumb">
  <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
  <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->siret ?>)</a></li>

</ol>

<section id="principal">
<?php if(!$isTeledeclarationMode): ?>
  <div class="row" id="formEtablissementChoice">
    <div class="col-xs-12">
      <?php include_component('subvention', 'formEtablissementChoice') ?>
    </div>
  </div>
<?php endif; ?>
  <div class="row">
    <div class="col-xs-12">
      <h2>Contrat Relance Viti</h2>

      <p>Espace des demandes d’aides dans le cadre du dispositif Contrat Relance Viti</p>
    </div>
  </div>
  <div class="row row-condensed">
    <div class="col-xs-12">
      <div class="panel panel-default" style="height:250px;">
        <div class="panel-heading"><h3 class="panel-title">Dossiers en cours : </h3></div>
        <div class="panel-body">
          <?php if($subvention_en_cours && $subvention_en_cours->isApprouve()): ?>
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-10 col-sm-offset-1">Votre subvention pour le <?php echo $operation_en_cours; ?> a été approuvée.</label>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <div class="col-sm-10 col-sm-offset-1">
              <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Visualiser votre demande de subvention <?php echo $operation_en_cours; ?> </a>
            </div>
          </div>
        <?php elseif($subvention_en_cours && $subvention_en_cours->isApprouvePartiellement()): ?>
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-10 col-sm-offset-1">Votre subvention pour le <?php echo $operation_en_cours; ?> a été approuvée partiellement.</label>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <div class="col-sm-10 col-sm-offset-1">
              <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Visualiser votre demande de subvention <?php echo $operation_en_cours; ?> </a>
            </div>
          </div>
        <?php elseif($subvention_en_cours && $subvention_en_cours->isRefuse()): ?>
          <div class="form-group" style="margin-bottom: 0">
            <label class="col-sm-10 col-sm-offset-1">Votre subvention pour le <?php echo $operation_en_cours; ?> a été refusée.</label>
          </div>
          <div class="form-group" style="margin-bottom: 0">
            <div class="col-sm-10 col-sm-offset-1">
              <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Visualiser votre demande de subvention <?php echo $operation_en_cours; ?> </a>
            </div>
          </div>
        <?php elseif($subvention_en_cours && $subvention_en_cours->isValide()): ?>
            <div class="form-group" style="margin-bottom: 0">
              <label class="col-sm-10 col-sm-offset-1">Votre subvention pour le <?php echo $operation_en_cours; ?> a été validée par vos soin. Elle est en cours d'étude par votre interprofession.</label>
            </div>
            <div class="form-group" style="margin-bottom: 0">
              <div class="col-sm-10 col-sm-offset-1">
                <a href="<?php echo url_for('subvention_visualisation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Visualiser votre demande de subvention <?php echo $operation_en_cours; ?> </a>
              </div>
            </div>
          <?php elseif($subvention_en_cours): ?>
              <div class="form-group" style="margin-bottom: 0">
                <label class="col-sm-10 col-sm-offset-1">Votre subvention pour le <?php echo $operation_en_cours; ?> est en cours de saisie.</label>
              </div>
              <div class="form-group" style="margin-bottom: 0">
                <div class="col-sm-10 col-sm-offset-1">
                  <a href="<?php echo url_for('subvention_infos',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Accèder à la saisie de votre demande de subvention <?php echo $operation_en_cours; ?> </a>
                </div>
              </div>
            <?php else: ?>
                <div class="form-group" style="margin-bottom: 0">
                  <label class="col-sm-10 col-sm-offset-1">Espace Contrat Relance Viti</label>
                </div>
                <div class="form-group" style="margin-bottom: 0">
                  <div class="col-sm-10 col-sm-offset-1">
                    <a href="<?php echo url_for('subvention_creation',array('identifiant' => $etablissement->identifiant, 'operation' => $operation_en_cours)); ?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Création d’une nouvelle demande d’aide régionale <?php echo $operation_en_cours; ?> </a>
                  </div>
                </div>
        <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</section>
