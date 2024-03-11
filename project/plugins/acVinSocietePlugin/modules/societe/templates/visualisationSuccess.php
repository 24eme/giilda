<?php use_helper('Compte') ?>
<ol class="breadcrumb">
    <?php if(!$sf_user->hasCredential('contacts')): ?>
        <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>">Contacts</a></li>
    <?php else: ?>
        <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <?php endif; ?>
    <li class="active"><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($societe->getRawValue()) ?>"></span> <?php echo $societe->raison_sociale; ?>  (<?php echo $societe->identifiant ?>)</a></li>
</ol>

<div class="row">
    <div class="col-xs-12 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-8 col-sm-9">
                        <h4><span class="<?php echo comptePictoCssClass($societe->getRawValue()) ?>"></span> Societe n° <?php echo $societe->identifiant; ?></h4>
                    </div>
                    <div class="col-xs-4 col-sm-3 text-muted text-right">
                        <?php if($modifiable): ?>
                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Modifier <span class="caret"></span></a>
                            <ul class="dropdown-menu text-left">
                                <li<?php echo ($societe->isSuspendu()) ? ' class="disabled"' : ''; ?>><a href="<?php echo ($societe->isSuspendu()) ? 'javascript:void(0)' : url_for('societe_modification', $societe); ?>">Editer</a></li>
                                <li<?php echo ($societe->isSuspendu())? ' class="disabled"' : ''; ?>><a href="<?php echo ($societe->isSuspendu())? 'javascript:void(0)' : url_for('societe_switch_statut', array('identifiant' => $societe->identifiant)); ?>">Archiver</a></li>
                                <li<?php echo ($societe->isActif()   )? ' class="disabled"' : ''; ?>><a href="<?php echo ($societe->isActif())? 'javascript:void(0)' : url_for('societe_switch_statut', array('identifiant' => $societe->identifiant)); ?>">Activer</a></li>
                                <li><a href="<?php echo url_for('compte_switch_en_alerte', array('identifiant' => $societe->getMasterCompte()->identifiant)); ?>"><?php echo ($societe->getMasterCompte()->exist('en_alerte') && $societe->getMasterCompte()->en_alerte)? 'Retirer alerte' : 'Mettre en alerte' ?></a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel-body panel-primary-bordered-right">
                <h2>
                	<?php echo $societe->raison_sociale; ?>
                	<?php if ($societe->getMasterCompte()->isSuspendu()): ?>
					    <span class="label label-default pull-right" style="padding-top: 0;"><small style="font-weight: inherit; color: inherit;"><?php echo $societe->getMasterCompte()->getStatutLibelle(); ?></small></span>
					<?php endif; ?>
                    <?php if ($societe->getMasterCompte()->exist('en_alerte') && $societe->getMasterCompte()->en_alerte): ?><span class="pull-right">⛔</span><?php endif; ?>
                </h2>
                <hr/>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="row">
                            <?php if ($societe->identifiant): ?>
                                <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">Identifiant&nbsp;:</div>
                                <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo $societe->identifiant; ?></div>
                            <?php endif; ?>
                            <?php if ($societe->code_comptable_client): ?>
                                <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">Comptable&nbsp;:</div>
                                <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo $societe->code_comptable_client; ?></div>
                            <?php endif; ?>
                            <?php if ($societe->siret): ?>
                                <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">SIRET&nbsp;:</div>
                                <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo formatSIRET($societe->siret); ?></div>
                            <?php endif; ?>
                            <?php if ($societe->code_naf): ?>
                                <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">Code naf&nbsp;:</div>
                                <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo $societe->code_naf; ?></div>
                            <?php endif; ?>
                            <?php if ($societe->no_tva_intracommunautaire): ?>
                                <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">TVA&nbsp;Intracom.&nbsp;:</div>
                                <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo $societe->no_tva_intracommunautaire; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-xs-7" style="border-left: 1px solid #eee">
                        <?php include_partial('compte/visualisationAdresse', array('compte' => $societe->getMasterCompte())); ?>
                    </div>
                </div>
                <hr />
                <h5 style="margin-bottom: 15px; margin-top: 15px;" class="text-muted"><strong>Télédéclaration</strong></h5>
                <?php include_partial('compte/visualisationLogin', array('compte' => $societe->getMasterCompte())); ?>
                <hr />
                <?php if ($societe->commentaire && $modifiable) : ?>
                <h5 style="margin-bottom: 15px; margin-top: 0px;"><strong>🗣 Commentaire️</strong></h5>
                <p>🗨️ <?php echo implode('</p><p>🗨️ ', $societe->getCommentaires()->getRawValue()); ?></p>
                <hr />
                <?php endif; ?>
                <?php if (MandatSepaConfiguration::getInstance()->isActive()): ?>
                <h5 style="margin-bottom: 15px; margin-top: 15px;" class="text-muted"><strong>Coordonnées bancaires</strong></h5>
                <?php if ($mandatSepa): ?>
                  <div class="row">
                    <div style="margin-bottom: 5px;" class="col-xs-1  text-muted">RUM&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-5"><?php echo $mandatSepa->debiteur->identifiant_rum; ?></div>
                    <div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Mandat généré&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-3">
                      <a href="<?php echo url_for('mandatsepa_pdf', $mandatSepa) ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-file"></span>&nbsp;PDF</a>
                    </div>
                  </div>
                  <div class="row" style="margin-top: 5px;">
                    <div style="margin-bottom: 5px;" class="col-xs-1  text-muted">IBAN&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-5"><?php echo $mandatSepa->debiteur->iban; ?></div>
                    <div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Mandat signé&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-3"><input type="checkbox" data-on-text="<span class='glyphicon glyphicon-ok-sign'></span>" data-off-text="<span class='glyphicon'></span>" class="bsswitch ajax" data-size="mini"<?php if ($mandatSepa->is_signe): ?> checked="checked" disabled<?php endif; ?> onchange="document.location='<?php echo url_for('societe_mandat_sepa_switch_signe', array('identifiant' => $societe->identifiant)); ?>'" /></div>
                  </div>
                  <div class="row" style="margin-top: 5px;">
                    <div style="margin-bottom: 5px;" class="col-xs-1 text-muted">BIC&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-5"><?php echo $mandatSepa->debiteur->bic; ?></div>
                    <?php if ($mandatSepa->is_signe): ?>
                    <div style="margin-bottom: 5px;" class="col-xs-3  text-muted">Prélèvement actif&nbsp;</div>
                    <div style="margin-bottom: 5px;" class="col-xs-3"><input type="checkbox" data-on-text="<span class='glyphicon glyphicon-ok-sign'></span>" data-off-text="<span class='glyphicon'></span>" class="bsswitch ajax" data-size="mini"<?php if($mandatSepa->is_actif): ?> checked="checked"<?php endif; ?> onchange="document.location='<?php echo url_for('societe_mandat_sepa_switch_actif', array('identifiant' => $societe->identifiant)); ?>'" /></div>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  <p class="text-muted">Aucun mandat de prélèvement SEPA n'a été saisi</p>
                <?php endif; ?>
                <hr />
                <?php endif; ?>
                <h5 style="margin-bottom: 15px; margin-top: 15px;" class="text-muted"><strong>Informations complémentaires</strong></h5>
                <?php include_partial('compte/visualisationTags', array('compte' => $societe->getMasterCompte(), 'modifiable' => $modifiable)); ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <?php include_component('societe', 'sidebar', array('societe' => $societe, 'activeObject' => $societe, 'modifiable' => $modifiable)); ?>
    </div>
</div>
