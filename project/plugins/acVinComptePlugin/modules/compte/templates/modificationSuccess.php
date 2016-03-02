<!-- #principal -->
<section id="principal">
    <ol class="breadcrumb">
        <li><a href="<?php echo url_for('societe'); ?>">Contacts</a></li>
        <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>"><span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo $societe->raison_sociale; ?></a></li>

        <li class="active">
            <strong><?php echo (!$compte->isNew()) ? $compte->nom_a_afficher : 'Nouvel interlocuteur'; ?></strong>
        </li>

    </ol>
    <!-- #contacts -->
    <section id="contacts">
        <div id="nouveau_contact" class="col-md-8 col-md-offset-2">
            <h2><?php echo (!$compte->isNew()) ? $compte->nom_a_afficher : 'Nouvel interlocuteur'; ?></h2>


            <form action="<?php echo ($compte->isNew()) ? url_for('compte_ajout', array('identifiant' => $societe->identifiant)) : url_for('compte_modification', array('identifiant' => $compte->identifiant)); ?>" method="post" class="form-horizontal">
                <div id="detail_contact" class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">Détail de l'interlocuteur</h3></div>
                    <div class="panel-body">
                        <?php
                        echo $compteForm->renderHiddenFields();
                        echo $compteForm->renderGlobalErrors();
                        ?>
                       
                        <div class="form-group">
                            <?php echo $compteForm['civilite']->renderError(); ?>
                            <?php echo $compteForm['civilite']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                            <div class="col-xs-8"><?php echo $compteForm['civilite']->render(); ?></div>
                        </div>
                        <div class="form-group">
                            <?php echo $compteForm['prenom']->renderError(); ?>
                            <?php echo $compteForm['prenom']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                            <div class="col-xs-8"><?php echo $compteForm['prenom']->render(); ?></div>
                        </div>
                        <div class="form-group">
                            <?php echo $compteForm['nom']->renderError(); ?>
                            <?php echo $compteForm['nom']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                            <div class="col-xs-8"><?php echo $compteForm['nom']->render(); ?></div>
                        </div>
                        <div class="form-group">
                            <?php echo $compteForm['fonction']->renderError(); ?>
                            <?php echo $compteForm['fonction']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                            <div class="col-xs-8"><?php echo $compteForm['fonction']->render(); ?></div>
                        </div>                
                        <div class="form-group">
                            <?php echo $compteForm['commentaire']->renderError(); ?>
                            <?php echo $compteForm['commentaire']->renderLabel(null, array("class" => "col-xs-4 control-label")); ?>
                            <div class="col-xs-8"><?php echo $compteForm['commentaire']->render(); ?></div>
                        </div> 
                    </div>
                </div>

               
                <?php include_partial('compte/modificationCoordonnee', array('compteForm' => $compteForm, 'compteSociete' => $compte->getSociete()->getMasterCompte())) ?>

                <div class="col-xs-6">
                    <?php if ($compte->isNew()): ?>
                        <a href="<?php echo url_for('societe_visualisation', $societe); ?>" class="btn btn-default">Annuler</a>
                    <?php else: ?>
                        <a href="<?php echo url_for('compte_visualisation', $compte); ?>" class="btn btn-default">Annuler</a>
                    <?php endif; ?>
                </div><div class="col-xs-6 text-right">
                    <button class="btn btn-success">Valider</button>
                </div>

            </form>

        </div>
    </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn btn-default"><span>Accueil des sociétés</span></a>
        </div>
    </div>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>" class="btn btn-default"><span>Accueil de la société</span></a>
        </div>
    </div>
    <?php if (!$compte->isNew()) : ?>
        <div class="contenu">
            <div class="btnRetourAccueil">
                <a href="<?php echo url_for('compte_visualisation', array('identifiant' => $compte->identifiant)); ?>" class="btn btn-default"><span>Retour à la visualisation</span></a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
end_slot();
?> 
