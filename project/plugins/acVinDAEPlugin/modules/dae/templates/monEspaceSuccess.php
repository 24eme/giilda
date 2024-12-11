<?php use_helper('Date'); ?>
<?php include_partial('dae/preTemplate'); ?>
<?php include_partial('dae/breadcrum', array('etablissement' => $etablissement)); ?>

<div class="row">
    <div class="col-xs-12" id="daeFormEtablissement">
        <?php include_component('dae', 'formEtablissementChoice', array('identifiant' => $etablissement->_id)) ?>
    </div>
</div>

<h1>Données de commercialisation</h1>
<div class="row" style="margin:0;">
	<div class="col-xs-6">
		<div class="row"><a class="btn btn-sm btn-default" href="<?php echo url_for('dae_export_edi', array('sf_subject' => $etablissement, 'campagne' => $campagne)) ?>"><span class=" glyphicon glyphicon-cloud-download"></span> Voir le fichier de mes commercialisations <?php echo $campagne ?></a></div>
	</div>
	<div class="col-xs-6">
        <div class="row text-right">
        	<a class="btn btn-default" href="<?php echo url_for('dae_upload_fichier_edi', array('identifiant' => $etablissement->identifiant, 'periode' => $periode->format('Y-m-d'), 'md5' => "0")); ?>"><span class="glyphicon glyphicon-cloud-upload"></span> Déposer le fichier de mes commercialisations</a>
        </div>
    </div>
</div>

<h1 style="margin-top: 20px;">Liste des ventes</h1>
<div class="row" style="margin:0;">
    <div class="col-xs-3" style="padding:0;">
        <form class="form-inline" method="get">
        	<?php echo $formCampagne->renderGlobalErrors() ?>
            <?php echo $formCampagne->renderHiddenFields() ?>
		    <div class="form-group<?php if($formCampagne['periode']->hasError()): ?> has-error<?php endif; ?>">
		        <?php echo $formCampagne['periode']->renderError(); ?>
		        <?php echo $formCampagne['periode']->render(); ?>
		    </div>
		    <button type="submit" class="btn btn-default" style="padding-top:9px;padding-bottom:3px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                  <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                </svg>
            </button>
        </form>
    </div>

    <div class="col-xs-8 form-horizontal" style="padding:0;">
    	<div class="form-group">
    		<label class="col-xs-2 control-label" for="quickfind" style="padding-right:5px;">Filtres</label>
    		<div class="col-xs-10" style="padding-left:5px;">
        		<input type="text" id="quickfind" class="input-sm form-control" placeholder="Filtrer les ventes" />
        	</div>
        </div>
	</div>
</div>
        <div class="row">
        	<?php include_partial('dae/recap', array('etablissement' => $etablissement, 'periode' => $periode, 'daes' => $daes)); ?>
        </div>
    </div>


	<script type="text/javascript">
	$('.table-filter').tableFilter({additionalFilterTriggers: [$('#quickfind')]});
	</script>
<?php use_javascript('lib/picnet.table.filter.min.js') ?>
<?php include_partial('dae/postTemplate'); ?>
