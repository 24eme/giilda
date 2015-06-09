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

<?php include_partial('vrac/etapes', array('vrac' => $form->getObject(), 'compte' => $compte, 'actif' => 1, 'urlsoussigne' => $urlForm,'isTeledeclarationMode' => $isTeledeclarationMode)); ?>

<div class="page-header">
    <h2>Création</h2>
</div>

<form 
	id="contrat_soussignes" 
	data-numcontrat="<?php echo ($nouveau)? null : $form->getObject()->numero_contrat ;?>" 
	data-isteledeclare="<?php echo ($isTeledeclarationMode)? 1 : 0 ;?>" 
	data-etablissementprincipal="<?php echo $etablissementPrincipal->_id ?>" 
	data-iscourtierresponsable="<?php echo ($isCourtierResponsable)? 1 : 0 ?>"
	action="<?php echo $urlForm; ?>" 
	method="post" 
	class="form-horizontal"
>
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?php echo $form['vendeur_identifiant']->renderError(); ?>
                <?php echo $form['vendeur_identifiant']->renderLabel("Vendeur :", array('class' => 'col-sm-2 control-label')); ?>
                <div class="col-sm-6" id="vendeur_choice">
                    <?php echo $form['vendeur_identifiant']->render(array('class' => 'form-control')); ?>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-body" id="vendeur_informations">
                            <?php include_partial('vrac/vendeurInformations', array('soussigne' => $form->getObject()->getVendeurObject())); ?>
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="form-group">
                <?php echo $form['acheteur_identifiant']->renderError(); ?>
                <?php echo $form['acheteur_identifiant']->renderLabel("Acheteur :", array('class' => 'col-sm-2 control-label')); ?>
                <div class="col-sm-6" id="acheteur_choice">
                    <?php echo $form['acheteur_identifiant']->render(array('class' => 'form-control')); ?>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-body" id="acheteur_informations">
                            <?php include_partial('vrac/acheteurInformations', array('soussigne' => $form->getObject()->getAcheteurObject())); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <?php echo $form['mandataire_exist']->renderError(); ?>
                    <div class="checkbox">
                        <label for="<?php echo $form['mandataire_exist']->renderId(); ?>">
                            <?php echo $form['mandataire_exist']->render(); ?>
                            Décocher s'il n'y a pas de courtier
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form['mandatant']->renderError(); ?>
                <?php echo $form['mandatant']->renderLabel("Mandaté par :", array('class' => 'col-sm-2 control-label')); ?>
                <div class="col-sm-10">
                    <?php echo $form['mandatant']->render(array('class' => 'form-control')); ?>
                </div>
            </div>      
            <div class="form-group">
                <?php echo $form['mandataire_identifiant']->renderError(); ?>
                <?php echo $form['mandataire_identifiant']->renderLabel("Mandataire :", array('class' => 'col-sm-2 control-label')); ?>
                <div class="col-sm-6" id="mandataire_choice">
                    <?php echo $form['mandataire_identifiant']->render(array('class' => 'form-control')); ?>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-body" id="mandataire_informations">
                            <?php include_partial('vrac/mandataireInformations', array('soussigne' => $form->getObject()->getMandataireObject())); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($form['commercial'])): ?>
            <div class="form-group">
                <?php echo $form['commercial']->renderError(); ?>
                <?php echo $form['commercial']->renderLabel("Mandaté par :", array('class' => 'col-sm-2 control-label')); ?>
                <div class="col-sm-10">
                    <?php echo $form['commercial']->render(array('class' => 'form-control')); ?>
                </div>
            </div>
            <?php endif; ?> 
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 text-left">
            <?php if ($isTeledeclarationMode): ?>
                <a href="<?php echo url_for('vrac_societe', array('identifiant' => $etablissementPrincipal->identifiant)); ?>" class="btn btn-default">Annuler la saisie</a> 
            <?php else: ?>                        
                <a href="<?php echo url_for('vrac'); ?>" class="btn btn-default">Annuler la saisie</a> 
            <?php endif; ?>
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
?>
 