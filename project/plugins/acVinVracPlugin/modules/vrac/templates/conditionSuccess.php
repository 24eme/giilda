<?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'compte' => $compte, 'actif' => 3, 'urlsoussigne' => null, 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<form action="" method="post" class="form-horizontal">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="row">
        <div class="col-sm-12">
        	<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Paiement</h3>
                </div>
                <div class="panel-body">
		        	<?php if (isset($form['delai_paiement'])): ?>
		            <div class="form-group col-sm-6 <?php if($form['delai_paiement']->hasError()): ?>has-error<?php endif; ?>">
		                <?php echo $form['delai_paiement']->renderError(); ?>
		                <?php echo $form['delai_paiement']->renderLabel("Délai de paiement :", array('class' => 'col-sm-5 control-label')); ?>
		                <div class="col-sm-7">
		                    <?php echo $form['delai_paiement']->render(); ?>
		                </div>
		            </div>
		            <?php endif; ?>
		        	<?php if (isset($form['cvo_repartition'])): ?>
		            <div class="form-group col-sm-6 <?php if($form['cvo_repartition']->hasError()): ?>has-error<?php endif; ?>">
		                <?php echo $form['cvo_repartition']->renderError(); ?>
		                <?php echo $form['cvo_repartition']->renderLabel("Taux de courtage :", array('class' => 'col-sm-5 control-label')); ?>
		                <div class="col-sm-7">
		                    <?php echo $form['cvo_repartition']->render(); ?>
		                </div>
		            </div>
		            <?php endif; ?>
		        	<?php if (isset($form['moyen_paiement'])): ?>
		            <div class="form-group col-sm-6 <?php if($form['moyen_paiement']->hasError()): ?>has-error<?php endif; ?>">
		                <?php echo $form['moyen_paiement']->renderError(); ?>
		                <?php echo $form['moyen_paiement']->renderLabel("Moyen de paiement :", array('class' => 'col-sm-5 control-label')); ?>
		                <div class="col-sm-7">
		                    <?php echo $form['moyen_paiement']->render(); ?>
		                </div>
		            </div>
		            <?php endif; ?>
		        	<?php if (isset($form['tva'])): ?>
		            <div class="form-group col-sm-6 <?php if($form['tva']->hasError()): ?>has-error<?php endif; ?>">
		                <?php echo $form['tva']->renderError(); ?>
		                <?php echo $form['tva']->renderLabel("TVA :", array('class' => 'col-sm-5 control-label')); ?>
		                <div class="col-sm-7">
		                    <?php echo $form['tva']->render(); ?>
		                </div>
		            </div>
		            <?php endif; ?>
                </div>
           </div>
           
        	<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Retiraison</h3>
                </div>
                <div class="panel-body">
		        	<?php if (isset($form['date_limite_retiraison'])): ?>
		            <div class="form-group col-sm-6 <?php if($form['date_limite_retiraison']->hasError()): ?>has-error<?php endif; ?>">
                        <?php echo $form['date_limite_retiraison']->renderError(); ?>
                        <?php echo $form['date_limite_retiraison']->renderLabel("Date limite de retiraison :", array('class' => 'col-sm-5 control-label')); ?>
                        <div class='input-group date datepicker col-sm-7'>
                            <?php echo $form['date_limite_retiraison']->render(); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
		            </div>
		            <?php endif; ?>
		            <?php if(isset($form['clause_reserve_propriete'])): ?>
						<div class="form-group col-sm-6">
							<?php echo $form['clause_reserve_propriete']->renderError(); ?>
							<div class="checkbox col-sm-7 col-sm-offset-5">
								<label for="<?php echo $form['clause_reserve_propriete']->renderId(); ?>">
									<?php echo $form['clause_reserve_propriete']->render(); ?>
									Clause de réserve de propriété
								</label>
							</div>
						</div>
					<?php endif; ?>
                </div>
            </div>
           
        	<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Clauses</h3>
                </div>
                <div class="panel-body">
                	<div class="row col-sm-6 ">
			        	<?php if (isset($form['conditions_particulieres'])): ?>
			            <div class="<?php if($form['conditions_particulieres']->hasError()): ?>has-error<?php endif; ?>">
			                <?php echo $form['conditions_particulieres']->renderError(); ?>
			                <?php echo $form['conditions_particulieres']->renderLabel("Conditions particulières :"); ?>
			                <?php echo $form['conditions_particulieres']->render(); ?>
			            </div>
		            	<?php endif; ?>
		            </div>
                	<div class="row col-sm-6">
		            <?php if(isset($form['pluriannuel'])): ?>
						<div class="form-group">
							<?php echo $form['pluriannuel']->renderError(); ?>
							<div class="checkbox col-sm-7 col-sm-offset-5">
								<label for="<?php echo $form['pluriannuel']->renderId(); ?>">
									<?php echo $form['pluriannuel']->render(); ?>
									Contrat pluriannuel
								</label>
							</div>
						</div>
					<?php endif; ?>
		            <?php if(isset($form['autorisation_nom_vin'])): ?>
						<div class="form-group">
							<?php echo $form['autorisation_nom_vin']->renderError(); ?>
							<div class="checkbox col-sm-7 col-sm-offset-5">
								<label for="<?php echo $form['autorisation_nom_vin']->renderId(); ?>">
									<?php echo $form['autorisation_nom_vin']->render(); ?>
									Autorisation d'utilisation du nom du vin
								</label>
							</div>
						</div>
					<?php endif; ?>
		            
		            <?php if(isset($form['autorisation_nom_producteur'])): ?>
						<div class="form-group">
							<?php echo $form['autorisation_nom_producteur']->renderError(); ?>
							<div class="checkbox col-sm-7 col-sm-offset-5">
								<label for="<?php echo $form['autorisation_nom_producteur']->renderId(); ?>">
									<?php echo $form['autorisation_nom_producteur']->render(); ?>
									Autorisation d'utilisation du nom du producteur
								</label>
							</div>
						</div>
					<?php endif; ?>
				</div>
                </div>
            </div>

            
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4 text-left">
            <a href="<?php echo url_for('vrac_marche', $vrac); ?>" class="btn btn-default">Etape précédente</a>
        </div>
        <div class="col-xs-4 text-center">
            <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
                <a class="btn btn-default" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>">Supprimer le brouillon</a>
            <?php endif; ?>  
        </div>
        <div class="col-xs-4 text-right">
            <button type="submit" class="btn btn-default">Étape suivante</button>
        </div>
    </div>
</form>

<?php if ($isTeledeclarationMode): ?>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $(".champ_datepicker input").datepicker({
                showOn: "button",
                buttonImage: "/images/pictos/pi_calendrier.png",
                buttonImageOnly: true,
                dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
                monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre"],
                dateFormat: 'dd/mm/yy',
                firstDay: 1
            });
        });
    </script>
<?php endif; ?>


