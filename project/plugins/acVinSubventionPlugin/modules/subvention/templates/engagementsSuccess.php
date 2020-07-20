<?php include_partial('subvention/breadcrumb', array('subvention' => $subvention)); ?>

<section id="principal">
    <?php include_partial('subvention/etapes', array('subvention' => $subvention)); ?>

    <form class="form-horizontal" method="POST" action="">
        <?php echo $form->renderGlobalErrors(); ?>
        <?php echo $form->renderHiddenFields(); ?>

        <h2>Engagements</h2>

        <p>Merci de vérifier les points suivants et vous engager à les respecter :</p>

        <div class="row">
        	<div class="col-xs-9">
            	<?php
                foreach ($form->getEngagements() as $key => $libelle):
                   if (!isset($form["engagement_$key"])) continue;
                ?>
                <div class="form-group <?php if($form["engagement_$key"]->hasError()): ?>has-error<?php endif; ?>" style="margin-bottom:0;">
                	<div class="col-xs-12">
        				<?php echo $form["engagement_$key"]->renderError() ?>
        			</div>
    				<div class="col-xs-12 checkbox">
    					<label for="<?php echo $form["engagement_$key"]->renderId() ?>">
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
            <div class="col-xs-3">
                <?php include_partial('subvention/aide'); ?>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <div class="col-xs-6">
                <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('subvention_dossier', $subvention); ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Étape précédente</a>
            </div>
            <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-success">Étape suivante&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
    </form>
</section>
