<?php use_helper('Compte') ?>
<?php use_helper('Date'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <li class="<?php echo (!isset($etablissement) && !isset($interlocuteur)) ? "active" : "" ?>"><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($societe->getRawValue()) ?>"></span> <?php echo $societe->raison_sociale; ?></a></li>
    <?php if (isset($etablissement)): ?>
        <li class="active"><a href="<?php echo url_for('etablissement_visualisation', array('identifiant' => $etablissement->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($etablissement->getRawValue()) ?>"></span> <?php echo $etablissement->nom; ?></a></li>
    <?php endif; ?>
    <?php if (isset($interlocuteur)): ?>
        <li class="active"><a href="<?php echo url_for('compte_visualisation', array('identifiant' => $interlocuteur->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($interlocuteur->getRawValue()) ?>"></span> <?php echo ($interlocuteur->nom_a_afficher) ? $interlocuteur->nom_a_afficher : $interlocuteur->nom; ?></a></li>
    <?php endif; ?>
</ol>

<section id="principal" class="societe row">
    <div class="col-xs-12" style="<?php if (isset($etablissement) || isset($interlocuteur)): ?>opacity: 0.6<?php endif; ?>">
        <div class="list-group">
            <div class="list-group-item<?php echo ($societe->isSuspendu()) ? ' disabled': '' ?>">
                <div class="row">
                    <h2 style="margin-top: 5px; margin-bottom: 5px;" class="col-xs-10"><span class="<?php echo comptePictoCssClass($societe->getRawValue()) ?>"></span> <?php echo $societe->raison_sociale; ?>
                        <small class="text-muted">(n° de societe : <?php echo $societe->identifiant; ?>)</small>
                        <?php if ($modification || $reduct_rights) : ?>

                        <?php endif; ?>
                    </h2>
                    <div class="col-xs-2 text-right">
                      <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Modifier <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                         <li<?php echo ($societe->isSuspendu())? ' class="disabled"' : ''; ?>><a href="<?php echo ($societe->isSuspendu()) ? 'javascript:void(0)' : url_for('societe_modification', array('identifiant' => $societe->identifiant)); ?>">Editer</a></li>
                         <li<?php echo ($societe->isSuspendu())? ' class="disabled"' : ''; ?>><a href="<?php echo ($societe->isSuspendu()) ? 'javascript:void(0)' : url_for('societe_switch_statut', array('identifiant' => $societe->identifiant)); ?>">Suspendre</a></li>
                         <li<?php echo ($societe->isActif())? ' class="disabled"' : ''; ?>><a href="<?php echo ($societe->isActif()) ? 'javascript:void(0)' : url_for('societe_switch_statut', array('identifiant' => $societe->identifiant)); ?>">Activer</a></li>
                       </ul>
                      </div></div>
                </div>
                <div class="row">
                    <div class="col-xs-9">
                        <p class="lead" style="margin-bottom: 5px;">
                            <span class="label label-primary"><?php echo $societe->type_societe; ?></span>
                            <?php if ($societe->statut == SocieteClient::STATUT_SUSPENDU): ?>
                                <span class="label label-default"><?php echo $societe->statut; ?></span>
                                <?php endif; ?>
                            <small><?php if ($societe->date_creation) : ?><span class="label label-info">Crée le <?php echo format_date($societe->date_creation, 'dd/MM/yyyy'); ?></span>&nbsp;<?php endif; ?>
<?php if ($societe->date_modification) : ?>
                                    <span class="label label-info">Dernière modification le <?php echo format_date($societe->date_modification, 'dd/MM/yyyy'); ?></span>&nbsp;<?php endif; ?></small>
                        </p>
                    </div>
                </div>
            </div>

<?php include_partial('compte/coordonneesVisualisation', array('compte' => $societe->getMasterCompte(), 'modification' => $modification, 'reduct_rights' => $reduct_rights)); ?>


                <?php if ($societe->getMasterCompte()->exist('droits')): ?>
                <div class="list-group-item<?php echo ($societe->isSuspendu()) ? ' disabled': '' ?>">
    <?php if ($societe->getMasterCompte()->exist('droits') && $societe->getMasterCompte()->hasDroit(Roles::TELEDECLARATION)): ?>
                        <p>
                            <strong>Login de télédéclaration :</strong> <?php echo $societe->getMasterCompte()->getLogin(); ?>
                            <?php if (strpos($societe->getMasterCompte()->mot_de_passe, '{TEXT}') !== false) : ?>
                                <span class="text-muted">(code de création : <?php echo str_replace('{TEXT}', '', $societe->getMasterCompte()->mot_de_passe); ?>)</span>
                            <?php elseif (strpos($societe->getMasterCompte()->mot_de_passe, '{OUBLIE}') !== false): ?>
                                <?php $lien = 'https://'.sfConfig::get('app_routing_context_production_host').url_for("compte_teledeclarant_mot_de_passe_oublie_login", array("login" => $societe->getMasterCompte()->identifiant, "mdp" => str_replace("{OUBLIE}", "", $societe->getMasterCompte()->mot_de_passe))); ?>
        				                <span class="text-muted">(Mot de passe oublié : <?php echo $lien; ?> )</span>
                            <?php else: ?>
                                <span class="text-muted">(code de création : Compte déjà créé)</span>
                            <?php endif; ?>
                        </p>
                            <?php
                            if ($societe->isTransaction()):
                                if ($societe->getEtablissementPrincipal() && $societe->getEtablissementPrincipal()->getEmailTeledeclaration() && $societe->getMasterCompte()->isTeledeclarationActive()) :
                                    ?>
                                <p>Email de télédéclaration : <?php echo $societe->getEtablissementPrincipal()->getEmailTeledeclaration(); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ($societe->getEmailTeledeclaration() && $societe->getMasterCompte()->isTeledeclarationActive()) :
                                ?>
                                <p>Email de télédéclaration : <?php echo $societe->getEmailTeledeclaration(); ?></p>
                            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
                    <p><?php if ($societe->getMasterCompte()->exist('droits')): ?>
                            <strong>Droits :</strong>
                            <?php foreach ($societe->getMasterCompte()->getDroits() as $droit) : ?>
                                <label class="label label-default"><?php echo isset(Roles::$teledeclarationLibelles[$droit]) ? Roles::$teledeclarationLibelles[$droit] : $droit; ?></label>
                            <?php endforeach; ?>
                <?php endif; ?></p>
                </div>
<?php endif; ?>
            <div class="list-group-item<?php echo ($societe->isSuspendu()) ? ' disabled': '' ?>">
                <ul class="list-inline">
                    <li><attr>N° SIRET :</attr> <?php echo $societe->siret; ?></li>
                    <?php if ($societe->code_naf) : ?>
                        <li><attr>Code NAF :</attr> <?php echo $societe->code_naf; ?></li>
                    <?php endif; ?>
                    <?php if ($societe->code_comptable_client) : ?>
                        <li><attr>N° Compta Client :</attr> <?php echo $societe->code_comptable_client; ?></li>
                    <?php endif; ?>
                    <?php if ($societe->code_comptable_fournisseur) : ?>
                        <li><attr>N° Compta Fournisseur :</attr> <?php echo $societe->code_comptable_fournisseur; ?></li>
                    <?php endif; ?>
                        <?php if ($societe->no_tva_intracommunautaire) : ?>
                        <li>TVA intracom : <?php echo $societe->no_tva_intracommunautaire; ?>
                        <?php endif; ?>
                        <?php if ($societe->exist('type_fournisseur') && count($societe->type_fournisseur)) : ?>
                        <li>Type de Fournisseur : <?php foreach ($societe->type_fournisseur as $type_fournisseur) : ?> <?php echo $type_fournisseur; ?>&nbsp;<?php endforeach; ?>
                        <?php endif; ?>
                </ul>

                <?php if ($societe->commentaire) : ?>
                    <strong>Commentaires :</strong> <?php echo $societe->commentaire; ?>
                <?php endif; ?>
            </div>



            <?php if (MandatSepaConfiguration::getInstance()->isActive()): $mandatSepa = $societe->getMandatSepa(); ?>
            <div class="list-group-item<?php echo ($societe->isSuspendu()) ? ' disabled': '' ?>">
                <h5 style="margin-bottom: 15px; margin-top: 15px;" class="text-muted"><strong>Coordonnées bancaires</strong></h5>
                <?php if ($mandatSepa): ?>
                  <div class="row">
                    <div style="margin-bottom: 5px;" class="col-xs-1  text-muted">RUM&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-5"><?php echo $mandatSepa->debiteur->identifiant_rum; ?></div>
                    <div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Mandat généré&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-3"></div>
                  </div>
                  <div class="row" style="margin-top: 5px;">
                    <div style="margin-bottom: 5px;" class="col-xs-1  text-muted">IBAN&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-5"><?php echo $mandatSepa->debiteur->iban; ?></div>
                    <div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Mandat signé&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-3"><input type="checkbox" data-on-text="<span class='glyphicon glyphicon-ok-sign'></span>" data-off-text="<span class='glyphicon'></span>" class="bsswitch ajax" data-size="mini"<?php if ($mandatSepa->is_signe): ?> checked="checked"<?php endif; ?> disabled /></div>
                  </div>
                  <div class="row" style="margin-top: 5px;">
                    <div style="margin-bottom: 5px;" class="col-xs-1 text-muted">BIC&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-5"><?php echo $mandatSepa->debiteur->bic; ?></div>
                    <?php if ($mandatSepa->is_signe): ?>
                    <div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Prélèvement actif&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-3"><input type="checkbox" data-on-text="<span class='glyphicon glyphicon-ok-sign'></span>" data-off-text="<span class='glyphicon'></span>" class="bsswitch ajax" data-size="mini"<?php if($mandatSepa->is_actif): ?> checked="checked"<?php endif; ?> disabled /></div>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  <li>Aucun mandat de prélèvement SEPA n'a été saisi</li>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
        <?php foreach ($etablissements as $etablissementId => $etb) : ?>
        <div class="col-xs-12" style="<?php if ((isset($etablissement) && $etablissement->_id != $etablissementId) || isset($interlocuteur)): ?>opacity: 0.6<?php endif; ?>">
    <?php include_partial('etablissement/visualisation', array('etablissement' => $etb->etablissement, 'ordre' => $etb->ordre, 'fromSociete' => true, 'modification' => $modification, 'reduct_rights' => $reduct_rights)); ?>
        </div>
    <?php endforeach; ?>

    <?php foreach ($interlocuteurs as $interlocuteurId => $compte) : ?>
        <?php if(!$compte): continue; endif; ?>
            <?php if ($compte->isSocieteContact() || $compte->isEtablissementContact()): ?><?php continue; ?><?php endif; ?>
        <div class="col-xs-4" style="<?php if (isset($etablissement) || (isset($interlocuteur) && $interlocuteur->_id != $compte->_id)): ?>opacity: 0.6<?php endif; ?>">
    <?php include_partial('compte/visualisation', array('compte' => $compte, 'modification' => $modification, 'reduct_rights' => $reduct_rights)); ?>
        </div>
<?php endforeach; ?>

    <div style="margin-bottom: 20px;" class="col-xs-12 text-center">
        <div class="row">
            <?php if ($modification || $reduct_rights) : ?>
                <?php if (!$reduct_rights && $societe->canHaveChais()) : ?>
                    <div class="col-xs-6 text-right">
                        <a <?php echo ($societe->isSuspendu())? 'disabled="disabled"' : ''; ?> href="<?php echo url_for('etablissement_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Créer un établissement</a>
                    </div>
                <?php endif; ?>
                <div class="col-xs-6 text-left">
                    <a <?php echo ($societe->isSuspendu())? 'disabled="disabled"' : ''; ?> href="<?php echo url_for('compte_ajout', array('identifiant' => $societe->identifiant)); ?>" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Créer un interlocuteur</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
