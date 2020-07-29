<?php include_partial('subvention/breadcrumb', array('subvention' => $subvention)); ?>

<section id="principal" class="form-horizontal">
    <h1>
    	Récapitulatif de votre dossier de subvention <strong><?php echo $subvention->operation ?></strong>&nbsp;<small class="text-muted">- version <?php echo $subvention->version; ?></small>
      <?php if($subvention->isValideInterpro()): ?>
        <a href="<?php echo url_for('subvention_reouvrir', $subvention) ?>" class="btn btn-warning pull-right">Ré-ouvrir la demande</a>
      <?php endif; ?>
    </h1>

  <?php include_partial('subvention/recap', array('subvention' => $subvention)); ?>

  <?php if(!$isTeledeclarationMode && isset($formValidationInterpro)): ?>
    <hr/>
    <h1>
      Approbation du dossier
    </h1>
    <div class="row row-condensed">
    	<div class="col-xs-12">
    		<form class="form-horizontal" method="POST" action="" id="approbationForm">
          <?php include_partial('subvention/validationInterpro', array('form' => $formValidationInterpro, 'subvention' => $subvention)); ?>
  <?php endif; ?>

    <div class="row">
        <div class="col-xs-4">
            <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('subvention_etablissement', $subvention->getEtablissement()) ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Retour à mon espace</a>
        </div>
        <div class="col-xs-4 text-center">
        	<div class="btn-group" role="group">
				<a href="<?php echo url_for('subvention_zip', $subvention) ?>" class="btn btn-default"><span class="glyphicon glyphicon-download-alt"></span>&nbsp;Télécharger le dossier complet</a>
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><a href="<?php echo url_for('subvention_pdf', $subvention) ?>">Fiche de pré-qualification (PDF)</a></li>
                    <li><a href="<?php echo url_for('subvention_xls', $subvention) ?>">Descriptif détaillé de l'opération (Excel)</a></li>
              	</ul>
			</div>
        </div>
        <div class="col-xs-4 text-right">
            <?php if(!$isTeledeclarationMode && isset($formValidationInterpro)): ?>
              <input type="hidden" class="approbationstatut" value="" name="statut"/>

              <div class="btn-group" role="group">
                <a class="btn btn-success formPostButton" data-statut="<?php echo SubventionClient::STATUT_APPROUVE; ?>" >&nbsp;Approuver le dossier</a>
            		<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
            		<ul class="dropdown-menu">
                    <a href="#" class="text-danger formPostButton" data-statut="<?php echo SubventionClient::STATUT_REFUSE; ?>" >&nbsp;Refuser le dossier</a>
                </ul>
    			    </div>
            <?php else: ?>
              <a href="https://mesaidesenligne.laregion.fr/" target="_blank" class="btn btn-success">Vers le site de la région Occitanie&nbsp;<span class="glyphicon glyphicon-log-out"></span></a>
            <?php endif; ?>
        </div>
    </div>
    <?php if(!$isTeledeclarationMode && isset($formValidationInterpro)): ?>
        </form>
      </div>
    </div>
    <?php endif; ?>

    <script type="text/javascript">

        $(document).ready( function()
    	   {
          $(".formPostButton").click(function(e){
            e.preventDefault();
            var statut = $(this).data("statut");
            $("input.approbationstatut").val(statut);
            $("#approbationForm").submit();
          });
        });
    </script>
