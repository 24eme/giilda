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

