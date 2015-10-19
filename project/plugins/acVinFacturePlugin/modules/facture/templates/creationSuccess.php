<?php
use_helper('Float');
?>    

<p id="fil_ariane"><a href="<?php echo url_for('facture') ?>">Page d'accueil</a> &gt; <strong><?php echo $societe->raison_sociale ?></strong></p>
<div id="contenu_etape" class="col-xs-12">
    <?php include_component('facture', 'chooseSociete', array('identifiant' => $societe->identifiant)); ?> 
</div>
<div class="col-xs-12">
    <h2>Génération de facture</h2>
</div>

<?php if ($sf_user->hasFlash('notice')): ?>
    <div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
    <div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif; ?>
<div class="col-xs-12">
    <form method="post" action="" role="form" class="form-horizontal">
        <?php echo $formNouvelleFacture->renderHiddenFields(); ?>
        <?php echo $formNouvelleFacture->renderGlobalErrors(); ?>
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="form-group <?php if ($formNouvelleFacture["modele"]->hasError()): ?>has-error<?php endif; ?>">
                    <?php echo $formNouvelleFacture["modele"]->renderError() ?>
                    <?php echo $formNouvelleFacture["modele"]->renderLabel("Type de facture", array("class" => "col-xs-4 control-label")); ?>
                    <div class="col-xs-8">
                        <?php echo $formNouvelleFacture["modele"]->render(array("class" => "form-control input-lg")); ?>
                    </div>
                </div>
                <div class="form-group <?php if ($formNouvelleFacture["date_facturation"]->hasError()): ?>has-error<?php endif; ?>">
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
                <div class="form-group <?php if ($formNouvelleFacture["message_communication"]->hasError()): ?>has-error<?php endif; ?>">
                    <?php echo $formNouvelleFacture["message_communication"]->renderError(); ?>
                    <?php echo $formNouvelleFacture["message_communication"]->renderLabel("Cadre de communication", array("class" => "col-xs-4 control-label")); ?>
                    <div class="col-xs-8">
                       
                            <?php echo $formNouvelleFacture["message_communication"]->render(array("class" => "form-control input-lg")); ?>
                      
                    </div>
                </div>                
                <div class="form-group text-right">
                    <div class="col-xs-6 col-xs-offset-6">
                        <button class="btn btn-success btn-lg btn-block btn-upper" type="submit">Générer la facture</button>
                    </div>
                </div>
            </div>
        </div>
    </form> 
</div>