<?php

$urlForm = null;

if (($form->getObject()->isNew() && !isset($isTeledeclarationMode)) || ($form->getObject()->isNew() && !$isTeledeclarationMode)) :
    $urlForm = url_for('vrac_nouveau');
elseif ($form->getObject()->isNew() && isset($isTeledeclarationMode) && $isTeledeclarationMode) :
    if (isset($choixEtablissement) && $choixEtablissement):
        $urlForm = url_for('vrac_nouveau', array('choix-etablissement' => $choixEtablissement));
    else:
        $urlForm = url_for('vrac_nouveau', array('etablissement' => $etablissementPrincipal->identifiant));
    endif;
else :
    $urlForm = url_for('vrac_soussigne', $vrac);
endif;
?>

<?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'compte' => $compte, 'actif' => 1, 'urlsoussigne' => $urlForm,'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<form 
	id="contrat_soussignes" 
	data-numcontrat="<?php echo ($nouveau)? null : $form->getObject()->numero_contrat ;?>" 
	data-isteledeclare="<?php echo ($isTeledeclarationMode)? 1 : 0 ;?>" 
	data-etablissementprincipal="<?php if (isset($etablissementPrincipal) && $etablissementPrincipal) echo $etablissementPrincipal->_id ?>" 
	data-iscourtierresponsable="<?php echo (isset($isCourtierResponsable) && $isCourtierResponsable)? 1 : 0 ?>"
	action="<?php echo $urlForm; ?>" 
	method="post" 
	class="form-horizontal"
>
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <?php echo $form['responsable']->renderError(); ?>
    <div class="row">
        <div class="col-sm-12">
        	<?php if(isset($form['attente_original'])): ?>
            <div class="form-group <?php if($form['attente_original']->hasError()): ?>has-error<?php endif; ?>">
                <?php echo $form['attente_original']->renderError(); ?>
                <?php echo $form['attente_original']->renderLabel("En attente de l'original :", array('class' => 'col-sm-3 control-label')); ?>
                <div class="col-sm-9">
                    <?php echo $form['attente_original']->render(); ?>
                </div>
            </div>
            <?php endif; ?>
        	<?php if(isset($form['type_contrat'])): ?>
            <div class="form-group <?php if($form['type_contrat']->hasError()): ?>has-error<?php endif; ?>">
                <?php echo $form['type_contrat']->renderError(); ?>
                <?php echo $form['type_contrat']->renderLabel("Contrat pluriannuel :", array('class' => 'col-sm-3 control-label')); ?>
                <div class="col-sm-9">
                    <?php echo $form['type_contrat']->render(); ?>
                </div>
            </div>
            <?php endif; ?>
        	<?php if(isset($form['type_transaction'])): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><label>Type de transaction</label></h3>
                </div>
                <div class="panel-body">
                    <?php echo $form['type_transaction']->renderError(); ?>
                    <div class="form-group <?php if($form['type_transaction']->hasError()): ?>has-error<?php endif; ?>">
                        <div class="col-sm-12 text-center">
                            <?php echo $form['type_transaction']->render(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>            
            <div class="row">
        		<?php if(isset($form['vendeur_identifiant'])): ?>
                <div class="col-xs-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><label>Vendeur</label>
                            <?php if(isset($form['responsable'])): ?>
                            <label class="responsable text-right pull-right <?php if($vrac->getOrAdd('responsable') == 'vendeur'): ?> text-primary<?php else: ?> text-muted<?php endif; ?>">
							    <input autocomplete="off" type="radio" name="vrac[responsable]" id="vrac_responsable_vendeur" value="vendeur" autocomplete="off"<?php if($vrac->getOrAdd('responsable') == 'vendeur'): ?> checked<?php endif; ?> /><span class="glyphicon glyphicon-user" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>
							</label>
							<?php endif; ?>
							</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div id="vendeur_selection" class="col-sm-12 <?php if($form['vendeur_identifiant']->getValue()): ?>hidden<?php endif; ?>">
                                    <?php echo $form['vendeur_identifiant']->renderError(); ?>
                                    <div class="form-group <?php if($form['vendeur_identifiant']->hasError()): ?>has-error<?php endif; ?>">
                                        <div class="col-sm-12" id="vendeur_choice">
                                            <?php echo $form['vendeur_identifiant']->render(array('class' => 'form-control select2 select-ajax', 'placeholder' => 'Séléctionner un vendeur', 'data-url' => url_for('vrac_soussigne_getinfos'), 'data-bloc' => '#vendeur_informations', 'data-hide' => '#vendeur_selection')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 text-center <?php if(!$form['vendeur_identifiant']->getValue()): ?>hidden<?php endif; ?>" id="vendeur_informations">
                                    <button type="button" class="btn btn-xs btn-default pull-right select-close" data-select="#<?php echo $form['vendeur_identifiant']->renderId() ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                    <div class="container-ajax">
                                        <?php if($form['vendeur_identifiant']->getValue()): ?>
                                        <?php include_partial('vrac/soussigne', array('id' => $form['vendeur_identifiant']->getValue(), 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-sm-12">
        							<?php if(isset($form['logement'])): ?>
                                    <div class="form-group <?php if($form['logement_exist']->hasError()): ?>has-error<?php endif; ?>">
                		                <div class="col-sm-12">
                		                    <?php echo $form['logement_exist']->renderError(); ?>
                		                    <div class="checkbox bloc_condition" data-condition-cible="#bloc_logement">
                		                        <label for="<?php echo $form['logement_exist']->renderId(); ?>">
                		                            <?php echo $form['logement_exist']->render(); ?>
                		                            Décocher si logement du vin différent
                		                        </label>
                		                    </div>
                		                </div>
                		            </div>
                		            <div id="bloc_logement" data-condition-value="0" class="form-group bloc_conditionner <?php if($form['logement']->hasError()): ?>has-error<?php endif; ?>">
                		                <?php echo $form['logement']->renderError(); ?>
                		                <div class="col-sm-12">
                		                    <?php echo $form['logement']->render(array("placeholder" => "Ville du logement")); ?>
                		                </div>
                		            </div> 
                		            <?php endif; ?>
        							<?php if(isset($form['vendeur_intermediaire']) && isset($form['representant_identifiant'])): ?>
                		            <div class="form-group col-sm-12">
                		            	<?php echo $form['vendeur_intermediaire']->renderError(); ?>
                		                <div class="checkbox">
                		                    <div class="checkbox bloc_condition" data-condition-cible="#bloc_intermediaire">
                		                	<label for="<?php echo $form['vendeur_intermediaire']->renderId(); ?>">
                		                    	<?php echo $form['vendeur_intermediaire']->render(); ?>
                		                    	Vendeur via intermedaire
                		                    </label>
                		                    </div>
                		                </div>
                		            </div>
                		            <div id="bloc_intermediaire" data-condition-value="1" class="form-group bloc_conditionner">
		                                <div id="representant_selection" class="col-sm-12 <?php if($form['representant_identifiant']->getValue()): ?>hidden<?php endif; ?>">
		                                    <?php echo $form['representant_identifiant']->renderError(); ?>
		                                    <div class="form-group <?php if($form['representant_identifiant']->hasError()): ?>has-error<?php endif; ?>">
		                                        <div class="col-sm-12" id="representant_choice">
		                                            <?php echo $form['representant_identifiant']->render(array('class' => 'form-control select2 select-ajax', 'placeholder' => 'Séléctionner un représentant', 'data-url' => url_for('vrac_soussigne_getinfos'), 'data-bloc' => '#representant_informations', 'data-hide' => '#representant_selection')); ?>
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="col-sm-12 text-center <?php if(!$form['representant_identifiant']->getValue()): ?>hidden<?php endif; ?>" id="representant_informations">
		                                    <button type="button" class="btn btn-xs btn-default pull-right select-close" data-select="#<?php echo $form['representant_identifiant']->renderId() ?>"><span class="glyphicon glyphicon-remove"></span></button>
		                                    <div class="container-ajax">
		                                        <?php if($form['representant_identifiant']->getValue()): ?>
		                                        <?php include_partial('vrac/soussigne', array('id' => $form['representant_identifiant']->getValue(), 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
		                                        <?php endif; ?>
		                                    </div>
		                                </div>
	                                </div>
                		            <?php endif; ?>
        							<?php if(isset($form['vendeur_tva'])): ?>
                                    <div class="form-group <?php if($form['vendeur_tva']->hasError()): ?>has-error<?php endif; ?>">
                		                <div class="col-sm-12">
                		                    <?php echo $form['vendeur_tva']->renderError(); ?>
                		                    <div class="checkbox">
                		                        <label for="<?php echo $form['vendeur_tva']->renderId(); ?>">
                		                            <?php echo $form['vendeur_tva']->render(); ?>
                		                            Le vendeur est assujetti à la TVA
                		                        </label>
                		                    </div>
                		                </div>
                		            </div>
                		            <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
        		<?php if(isset($form['acheteur_producteur']) || isset($form['acheteur_negociant'])): ?>
                <div class="col-xs-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><label>Acheteur</label>
                            <?php if(isset($form['responsable'])): ?>
                            <label class="responsable text-right pull-right<?php if($vrac->getOrAdd('responsable') == 'acheteur'): ?>  text-primary<?php else: ?> text-muted<?php endif; ?>">
							    <input autocomplete="off" type="radio" name="vrac[responsable]" id="vrac_responsable_acheteur" value="acheteur" autocomplete="off"<?php if($vrac->getOrAdd('responsable') == 'acheteur'): ?> checked<?php endif; ?> /><span class="glyphicon glyphicon-user" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>
							</label>
							<?php endif; ?>
							</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                            	<div id="bloc_acheteur_type" class="col-sm-12 <?php if($form['acheteur_producteur']->getValue() || $form['acheteur_negociant']->getValue()): ?>hidden<?php endif; ?>">
				                    <?php echo $form['acheteur_type']->renderError(); ?>
				                    <div class="form-group <?php if($form['acheteur_type']->hasError()): ?>has-error<?php endif; ?>">
				                        <div class="col-sm-12 bloc_condition" data-condition-cible="#bloc_producteur|#bloc_negociant">
				                            <?php echo $form['acheteur_type']->render(); ?>
				                        </div>
				                    </div>
                            	</div>
                            	<div id="bloc_producteur" data-condition-value="<?php echo EtablissementFamilles::FAMILLE_PRODUCTEUR?>">
	                                <div id="acheteur_producteur_selection" class="col-sm-12 <?php if($form['acheteur_producteur']->getValue()): ?>hidden<?php endif; ?>">
	                                    <?php echo $form['acheteur_producteur']->renderError(); ?>
	                                    <div class="form-group <?php if($form['acheteur_producteur']->hasError()): ?>has-error<?php endif; ?>">
	                                        <div class="col-sm-12" id="acheteur_producteur_choice">
	                                            <?php echo $form['acheteur_producteur']->render(array('class' => 'form-control select2 select-ajax', 'placeholder' => 'Séléctionner un acheteur', 'data-url' => url_for('vrac_soussigne_getinfos'), 'data-bloc' => '#acheteur_producteur_informations', 'data-hide' => '#acheteur_producteur_selection, #bloc_acheteur_type')); ?>
	                                        </div> 
	                                    </div>
	                                </div>
	                                <div class="col-sm-12 text-center <?php if(!$form['acheteur_producteur']->getValue()): ?>hidden<?php endif; ?>" id="acheteur_producteur_informations">
	                                    <button type="button" class="btn btn-xs btn-default pull-right select-close" data-select="#<?php echo $form['acheteur_producteur']->renderId() ?>"><span class="glyphicon glyphicon-remove"></span></button>
	                                    <div class="container-ajax">
	                                        <?php if($form['acheteur_producteur']->getValue()): ?>
	                                        <?php include_partial('vrac/soussigne', array('id' => $form['acheteur_producteur']->getValue(), 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
	                                        <?php endif; ?>
	                                    </div>
	                                </div>
	                            </div>
                            	<div id="bloc_negociant" data-condition-value="<?php echo EtablissementFamilles::FAMILLE_NEGOCIANT ?>">
	                                <div id="acheteur_negociant_selection" class="col-sm-12 <?php if($form['acheteur_negociant']->getValue()): ?>hidden<?php endif; ?>">
	                                    <?php echo $form['acheteur_negociant']->renderError(); ?>
	                                    <div class="form-group <?php if($form['acheteur_negociant']->hasError()): ?>has-error<?php endif; ?>">
	                                        <div class="col-sm-12" id="acheteur_negociant_choice">
	                                            <?php echo $form['acheteur_negociant']->render(array('class' => 'form-control select2 select-ajax', 'placeholder' => 'Séléctionner un acheteur', 'data-url' => url_for('vrac_soussigne_getinfos'), 'data-bloc' => '#acheteur_negociant_informations', 'data-hide' => '#acheteur_negociant_selection, #bloc_acheteur_type')); ?>
	                                        </div> 
	                                    </div>
	                                </div>
	                                <div class="col-sm-12 text-center <?php if(!$form['acheteur_negociant']->getValue()): ?>hidden<?php endif; ?>" id="acheteur_negociant_informations">
	                                    <button type="button" class="btn btn-xs btn-default pull-right select-close" data-select="#<?php echo $form['acheteur_negociant']->renderId() ?>"><span class="glyphicon glyphicon-remove"></span></button>
	                                    <div class="container-ajax">
	                                        <?php if($form['acheteur_negociant']->getValue()): ?>
	                                        <?php include_partial('vrac/soussigne', array('id' => $form['acheteur_negociant']->getValue(), 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
	                                        <?php endif; ?>
	                                    </div>
	                                </div>
	                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        	<?php if(isset($form['mandataire_identifiant'])): ?>
            <?php echo $form['mandataire_exist']->renderError(); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    	<span class="bloc_condition" data-condition-cible="#bloc_mandataire"  for="<?php echo $form['mandataire_exist']->renderId(); ?>"><?php echo $form['mandataire_exist']->render(); ?>&nbsp;<label for="vrac_mandataire_exist">Mandataire / Courtier</label></span>
                    <?php if(isset($form['responsable'])): ?>
                    <label class="responsable pull-right<?php if($vrac->getOrAdd('responsable') == 'mandataire'): ?>  text-primary<?php else: ?> text-muted<?php endif; ?>">
						<input autocomplete="off" type="radio" name="vrac[responsable]" id="vrac_responsable_mandataire" value="mandataire" autocomplete="off"<?php if($vrac->getOrAdd('responsable') == 'mandataire'): ?> checked<?php endif; ?> /><span class="glyphicon glyphicon-user" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Responsable"></span>
					</label>
					<?php endif; ?>
					</h3>
                </div>
                <div id="bloc_mandataire" data-condition-value="1" class="panel-body bloc_conditionner">
                    <div class="row">
                        <div class="col-sm-12 <?php if($form['mandataire_identifiant']->getValue()): ?>hidden<?php endif; ?>" id="mandataire_selection">
                            <?php echo $form['mandataire_identifiant']->renderError(); ?>
                            <div class="form-group <?php if($form['mandataire_identifiant']->hasError()): ?>has-error<?php endif; ?>">
                                <div class="col-sm-12" id="mandataire_choice">
                                    <?php echo $form['mandataire_identifiant']->render(array('class' => 'form-control select2 select-ajax', 'placeholder' => 'Séléctionner un mandataire', 'data-url' => url_for('vrac_soussigne_getinfos'), 'data-bloc' => '#mandataire_informations', 'data-hide' => '#mandataire_selection')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 text-center <?php if(!$form['mandataire_identifiant']->getValue()): ?>hidden<?php endif; ?>" id="mandataire_informations">
                            <button type="button" class="btn btn-xs btn-default pull-right select-close" data-select="#<?php echo $form['mandataire_identifiant']->renderId() ?>"><span class="glyphicon glyphicon-remove"></span></button>
                            <div class="container-ajax">
                            <?php if($form['mandataire_identifiant']->getValue()): ?>
                            <?php include_partial('vrac/soussigne', array('id' => $form['mandataire_identifiant']->getValue(), 'isTeledeclarationMode' => $isTeledeclarationMode)); ?>
                            <?php endif; ?>
                            </div>
                        </div>
                        <?php if (isset($form['commercial'])): ?>
                        <div class="col-sm-12">
                            <?php echo $form['commercial']->renderError(); ?>
                            <div class="form-group <?php if($form['commercial']->hasError()): ?>has-error<?php endif; ?>">
                                <?php echo $form['commercial']->renderLabel("Mandaté par :", array('class' => 'col-sm-2 control-label')); ?>
                                <div class="col-sm-10">
                                    <?php echo $form['commercial']->render(array('class' => 'form-control')); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 text-left">
            <?php if ($isTeledeclarationMode): ?>
                <a tabindex="-1" href="<?php echo url_for('vrac_societe', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Annuler la saisie</a> 
            <?php else: ?>                        
                <a tabindex="-1" href="<?php echo url_for('vrac'); ?>" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Annuler la saisie</a> 
            <?php endif; ?>
        </div>
        <div class="col-xs-4 text-center">
            <?php if ($isTeledeclarationMode && $vrac->isBrouillon()) : ?>
                <a tabindex="-1" class="btn btn-default" href="<?php echo url_for('vrac_supprimer_brouillon', $vrac); ?>">Supprimer le brouillon</a>
            <?php endif; ?>  
        </div>
        <div class="col-xs-4 text-right">
            <button type="submit" class="btn btn-success">Étape suivante <span class="glyphicon glyphicon-chevron-right"></span></button>
        </div>
    </div>
</form>

<?php
if ($isTeledeclarationMode):
    include_partial('colonne_droite', array('societe' => $societe, 'etablissementPrincipal' => $etablissementPrincipal));
else:
    slot('colApplications');
    /*
     * Inclusion du panel de progression d'édition du contrat
     */
    if (!$contratNonSolde)
        include_partial('contrat_progression', array('vrac' => $vrac));

    /*
     * Inclusion des Contacts
     */
    end_slot();
endif;
