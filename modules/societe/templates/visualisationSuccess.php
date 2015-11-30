<ol class="breadcrumb">
    <li><a href="<?php echo url_for('societe') ?>">Page d'accueil</a></li>
    <li class="active"><?php echo $societe->raison_sociale; ?></li>
</ol>

<section class="row">
    <div class="col-xs-9">
        <h2><?php echo $societe->raison_sociale; ?></h2>

        <div class="row">
            <?php include_partial('visualisationPanel', array('societe' => $societe, 'modification' => $modification, 'reduct_rights' => $reduct_rights)); ?>
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Coordonnées de la société</div>
                    <div class="panel-body">
                        <div class="row">
                            <?php include_partial('compte/coordonneesVisualisation', array('compte' => $societe->getMasterCompte())); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($societe->getMasterCompte()->exist('droits') && $societe->getMasterCompte()->hasDroit(Roles::TELEDECLARATION)): ?>
            <div id="detail_societe_coordonnees" class="form_section ouvert">
                <h3>Télédeclaration</h3>
                <div class="form_contenu">
                    <div class="form_ligne">  
                        <label for="teledeclaration_login" class="label_liste">
                            Login :
                        </label>
                        <?php echo $societe->identifiant; ?>
                    </div>  
                    <?php
                    if ($societe->isTransaction()):
                        if ($societe->getEtablissementPrincipal() && $societe->getEtablissementPrincipal()->getEmailTeledeclaration() && $societe->getMasterCompte()->isTeledeclarationActive()) :
                            ?>
                            <div class="form_ligne">  
                                <label for="teledeclaration_email" class="label_liste">
                                    Email :
                                </label>
                            <?php echo $societe->getEtablissementPrincipal()->getEmailTeledeclaration(); ?>
                            </div>  
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($societe->getEmailTeledeclaration() && $societe->getMasterCompte()->isTeledeclarationActive()) :
                            ?>
                            <div class="form_ligne">  
                                <label for="teledeclaration_email" class="label_liste">
                                    Email :
                                </label>
                            <?php echo $societe->getEmailTeledeclaration(); ?>
                            </div>  
                        <?php endif; ?>
                    <?php endif; ?>
                    
    <?php if ($societe->getMasterCompte()->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_NOUVEAU) : ?>
                        <div class="form_ligne">  
                            <label for="teledeclaration_mot_de_passe" class="label_liste">
                                Code de création : 
                            </label>
                        <?php echo str_replace('{TEXT}', '', $societe->getMasterCompte()->mot_de_passe); ?>
                        </div>    
    <?php else: ?>
                        <div class="form_ligne">  
                            <label for="teledeclaration_email" class="label_liste">
                                Code de création : 
                            </label>
                            Compte déjà crée
                        </div>    
    <?php endif; ?>
                </div>
            </div>                 
    <?php endif; ?>


        <?php if (count($etablissements)): ?>
        <?php endif; ?>
        <?php
        foreach ($etablissements as $etablissementId => $etb) :
            include_partial('etablissement/visualisation', array('etablissement' => $etb->etablissement, 'ordre' => $etb->ordre, 'fromSociete' => true));
        endforeach;
        ?>
    </div>
    <div class="col-xs-3">
        <?php if ($modification || $reduct_rights) : ?>  
            <a href="<?php echo url_for('compte_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn btn-default btn-block">Nouvel interlocuteur</a>
            <?php if (!$reduct_rights && $societe->canHaveChais()) : ?>  
                <a href="<?php echo url_for('etablissement_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn btn-default btn-block">Nouvel Etablissement</a>
            <?php endif; ?>
        <?php endif; ?>  
        <?php include_component('societe', 'getInterlocuteursWithSuspendus'); ?>
    </div>
</section>