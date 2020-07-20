<ol class="breadcrumb">
    <li><a href="<?php echo url_for('subvention') ?>">Subvention</a></li>
    <li><a href="<?php echo url_for('subvention_etablissement', array('identifiant' => $subvention->identifiant)) ?>"><?php echo $subvention->declarant->nom ?> (<?php echo $subvention->identifiant ?>)</a></li>
    <li class="active"><a href="">Demande de subvention <?php echo $subvention->operation ?></a></li>
</ol>

<section id="principal">
    <?php include_partial('subvention/etapes', array('subvention' => $subvention)); ?>

    <h2>Validation du dossier</h2>

    <p>Vous pouvez vérifier vos informations avant de soumettre la validation de votre dossier à l'interprofession</p>

    <form class="form-horizontal" role="form" action="<?php echo url_for("subvention_validation", $subvention) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form->renderGlobalErrors(); ?>

        <?php include_partial('subvention/recap', array('subvention' => $subvention)); ?>

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
            	<a href="<?php echo url_for('subvention_engagements', $subvention) ?>" tabindex="-1" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Etape précédente</a>
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