<?php
use_helper('Float');
?>    
<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="<?php echo url_for('facture') ?>">Page d'accueil</a> &gt; <strong><?php echo $societe->raison_sociale ?></strong></p>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <?php include_component('facture', 'chooseSociete', array('identifiant' => $societe->identifiant)); ?>
    </section>
    <br />

    <?php
    include_partial('historiqueFactures', array('societe' => $societe, 'factures' => $factures));
    ?>
    <hr />
    <h2>Génération de facture</h2>
    <br />
    <?php include_partial('facture/mouvements', array('mouvements' => $mouvements, 'societe' => $societe, 'form' => $form)) ?>
 
<?php if ($sf_user->hasFlash('notice')): ?>
    <div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
    <div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif; ?>

<form method="post" action="" role="form" class="form-horizontal">
    <?php echo $formNouvelleFacture->renderHiddenFields(); ?>
    <?php echo $formNouvelleFacture->renderGlobalErrors(); ?>
    <div class="row">
        <div class="col-xs-8 col-xs-offset-2">
            <div class="form-group <?php if($formNouvelleFacture["modele"]->hasError()): ?>has-error<?php endif; ?>">
                <?php echo $formNouvelleFacture["modele"]->renderError() ?>
                <?php echo $formNouvelleFacture["modele"]->renderLabel("Type de facture", array("class" => "col-xs-4 control-label")); ?>
                <div class="col-xs-8">
                <?php echo $formNouvelleFacture["modele"]->render(array("class" => "form-control input-lg")); ?>
                </div>
            </div>
            <div class="form-group <?php if($formNouvelleFacture["date_facturation"]->hasError()): ?>has-error<?php endif; ?>">
                <?php echo $formNouvelleFacture["date_facturation"]->renderError(); ?>
                <?php echo $formNouvelleFacture["date_facturation"]->renderLabel("Date de facturation", array("class" => "col-xs-4 control-label")); ?>
                <div class="col-xs-8">
                    <div class="input-group date-picker">
                        <?php echo $formNouvelleFacture["date_facturation"]->render(array("class" => "form-control input-lg", "placeholder" => "Date de facturation")); ?>
                        <div class="input-group-addon">
                            <span class="glyphicon-calendar glyphicon"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <div class="col-xs-6 col-xs-offset-6">
                    <button class="btn btn-default btn-lg btn-block btn-upper" type="submit">Générer la facture</button>
                </div>
            </div>
        </div>
    </div>
</form> 

<div class="row row-margin">
    <div class="col-xs-12">
        <?php if(count($factures)): ?>
        <div class="list-group">
            <?php foreach ($factures as $facture) : ?>
                <li class="list-group-item col-xs-12">
                    <span class="col-xs-1"><?php if($facture->isAvoir()): ?>AVOIR<?php else: ?>FACTURE<?php endif; ?></span>
                    <span class="col-xs-2">N° <?php echo $facture->numero_ava ?></span>
                    <span class="col-xs-2"><?php echo format_date($facture->date_facturation, "dd/MM/yyyy", "fr_FR"); ?></span>
                    <span class="col-xs-2 text-right"><?php echo echoFloat($facture->total_ttc); ?> € TTC</span>
                    <span class="col-xs-5 text-right">
                        <div class="btn-group text-left">
                             <?php if(!$facture->isPayee() && !$facture->isAvoir()): ?>
                                <a href="<?php echo url_for("facture_paiement", array("id" => $facture->_id)) ?>"  class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Saisir le Paiement</a>
                            <?php endif; ?>
                            <button type="button" class="btn btn-default btn-default-step btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Actions
                                  <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php if(!$facture->isPayee() && !$facture->versement_comptable): ?>
                                <li><a href="<?php if(!$facture->isPayee() && !$facture->versement_comptable): ?><?php echo url_for("facture_edition", array("id" => $facture->_id)) ?><?php endif; ?>">Modifier</a></li>
                                <?php else: ?>
                                    <li class="disabled"><a href="">Modifier</a></li>
                                <?php endif; ?>
                                <?php if(!$facture->isAvoir()): ?>
                                <li><a href="<?php echo url_for("facture_avoir", array("id" => $facture->_id)) ?>">Créér un avoir <small>(à partir de cette facture)</small></a></li>
                                <?php endif; ?>
                                <?php if(!$facture->isAvoir() && !$facture->isPayee() && !$facture->versement_comptable): ?>
                                    <li><a onclick='return confirm("Étes vous sûr de vouloir regénérer la facture ?");' href="<?php echo url_for("facture_regenerate", array("id" => $facture->_id)) ?>">Regénerer</a></li>
                                <?php elseif(!$facture->isAvoir()): ?>
                                    <li class="disabled"><a href="#">Regénerer</a></li>
                                <?php endif; ?>
                                <?php if(!$facture->isPayee() && !$facture->isAvoir()): ?>
                                <li><a href="<?php echo url_for("facture_paiement", array("id" => $facture->_id)) ?>">Saisir le Paiement</a></li>
                                <?php elseif($facture->isPayee() && !$facture->versement_comptable_paiement && !$facture->isAvoir()): ?>
                                <li><a href="<?php echo url_for("facture_paiement", array("id" => $facture->_id)) ?>">Modifier le paiement</a></li>
                                <?php elseif(!$facture->isAvoir()): ?>
                                    <li class="disabled"><a href="">Modifier le paiement</a></li>
                                <?php endif; ?>
                            </ul>
                            <a href="<?php echo url_for("facture_pdf", array("id" => $facture->_id)) ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-file"></span>&nbsp;Visualiser</a>
                        </div>
                    </span>
                    <span class="col-xs-12">
                        <?php if(!$facture->isPayee() && !$facture->isAvoir()): ?>
                            <span class="label label-warning">Paiement non reçu</span>
                        <?php endif; ?>
                        <?php if($facture->versement_comptable): ?>
			                 <span class="label label-success">Versé comptablement</span>
                        <?php endif; ?>
                        <?php if($facture->versement_comptable_paiement && !$facture->isAvoir()): ?>
                            <span class="label label-success">Paiement versé comptablement</span>
                        <?php endif; ?>

                        <?php if($facture->isPayee()): ?>
                        <span class="label label-success">Paiement&nbsp;de&nbsp;<?php echo echoFloat($facture->montant_paiement); ?> €&nbsp;reçu&nbsp;le&nbsp;<?php echo format_date($facture->date_paiement, "dd/MM/yyyy", "fr_FR"); ?>
                        <?php if($facture->reglement_paiement): ?>(<?php echo $facture->reglement_paiement ?>)<?php endif; ?></span>
                        <?php endif; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-center text-muted"><i>Aucune Facture</i></p>
        <?php endif; ?>
    </div>
</div>

</section>
<!-- fin #principal -->

<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('facture'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>

