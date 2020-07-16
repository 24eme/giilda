<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
    <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $subvention->identifiant)) ?>"><?php echo $subvention->declarant->nom ?> (<?php echo $subvention->identifiant ?>)</a></li>
    <li class="active"><a href=""><?php if($subvention->isNew()): ?>Ajouter<?php else: ?>Modifier<?php endif; ?></a></li>
</ol>

<section id="principal">
    <?php include_partial('subvention/etapes', array('subvention' => $subvention)); ?>

    <h1><strong>Etape 3</strong> - Validation du dossier</h1>

    <form class="form-horizontal" role="form" action="<?php echo url_for("subvention_validation", $subvention) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <div class="row">
        	<div class="col-xs-12">
        		<h3><?php echo $subvention->declarant->raison_sociale ?> (SIRET n°<?php echo $subvention->declarant->siret ?>)</h3>
    			<a href="<?php echo url_for("subvention_pdf", $subvention) ?>" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-file"></span>&nbsp;Vos informations</a>
    		</div>
    	</div>

        <div class="row">
        	<div class="col-xs-12">
        		<h3>Dossier joint</h3>
        		<a href="#" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-file"></span>&nbsp;XLS</a>
        	</div>
        </div>

        <div class="row">
        	<div class="col-xs-12">
        		<h3>Engagements</h3>
            	<?php
                foreach ($form->getEngagements() as $key => $libelle):
                   if (!isset($form["engagement_$key"])) continue;
                ?>
                <div class="form-group <?php if($form["engagement_$key"]->hasError()): ?>has-error<?php endif; ?>" style="margin-bottom:0;">
                	<div class="col-xs-12">
        				<?php echo $form["engagement_$key"]->renderError() ?>
        			</div>
    				<div class="col-xs-12 checkbox">
    					<label for="validation_<?php echo "engagement_$key" ?>">
        				<?php echo $form["engagement_$key"]->render() ?>&nbsp;<?php echo $libelle ?>
        				</label>
                  	</div>
              	</div>
              	<?php
                $engagementsPrecisions = $form->getEngagementsPrecisions();
                if (isset($engagementsPrecisions[$key])): 
                ?>
                <div class="row">
        			<div class="col-xs-offset-1 col-xs-11" style="padding-left:0;">
        			<?php 
                        foreach ($engagementsPrecisions[$key] as $k => $libelle):
                            if (!isset($form["precision_engagement_$key/$k"])) continue;
                    ?>
                    <div class="form-group <?php if($form["precision_engagement_$key/$k"]->hasError()): ?>has-error<?php endif; ?>" style="margin-bottom:0;">
                    	<div class="col-xs-12">
            				<?php echo $form["precision_engagement_$key/$k"]->renderError() ?>
            			</div>
        				<div class="col-xs-12 checkbox">
        					<label for="validation_precision_engagement_<?php echo $key.'_'.$k ?>">
            				<?php echo $form["precision_engagement_$key/$k"]->render(array("data-target" => "#validation_engagement_".$key)) ?>&nbsp;<?php echo $libelle ?>
            				</label>
                      	</div>
                  	</div>
                	<?php endforeach; ?>
                	</div>
                </div>
                <?php endif; ?>
              	<?php endforeach; ?>
    		</div>
        </div>

        <div class="row">
        	<div class="col-xs-12">
        		<h3>Commentaire</h3>
            	<div class="form-group <?php if($form['commentaire']->hasError()): ?>has-error<?php endif; ?>">
        			<div class="col-xs-12">
        				<?php echo $form['commentaire']->renderError() ?>
        			</div>
        			<div class="col-xs-10">
        				<?php echo $form['commentaire']->render(array('class' => 'form-control', 'placeholder' => "Détailler les opérations pour lesquelles vous souhaitez utiliser la subvention")) ?>
        			</div>
        		</div>
    		</div>
        </div>
        <br />
        <br />
        <div class="row row-margin row-button">
            <div class="col-xs-6">
            	<a href="<?php echo url_for('subvention_dossier', $subvention) ?>" tabindex="-1" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Etape précédente</a>
            </div>
            <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-success">Valider le dossier&nbsp;<span class="glyphicon glyphicon-ok"></span></button>
            </div>
        </div>
    </form>
</section>
<script type="text/javascript">
$(document).ready(function() {
	$("input[type='checkbox']").change(function () {
		var checkbox = $(this);
		if (checkbox.data("target")) {
			if (checkbox.is(':checked')) {
				$(checkbox.data("target")).prop("checked", true);
			}
		} else {
			if (!checkbox.is(':checked')) {
				$("input[data-target='#"+checkbox.attr("id")+"']").prop("checked", false);
			}
		}
	});
});
</script>