<?php use_helper('Compte') ?>
<ol class="breadcrumb">
    <?php if(!$sf_user->hasCredential('contacts')): ?>
        <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>">Contacts</a></li>
    <?php else: ?>
        <li><a href="<?php echo url_for('societe') ?>">Contacts</a></li>
    <?php endif; ?>
    <li><a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societe->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($societe->getRawValue()) ?>"></span> <?php echo $societe->raison_sociale; ?> (<?php echo $societe->identifiant ?>)</a></li>
    <li class="active"><a href="<?php echo url_for('compte_visualisation', array('identifiant' => $compte->identifiant)); ?>"><span class="<?php echo comptePictoCssClass($compte->getRawValue()) ?>"></span> <?php echo $compte->nom_a_afficher; ?></a></li>
</ol>

<div class="row">
    <div class="col-xs-12 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-8 col-sm-9">
                        <h4><span class="glyphicon glyphicon-user"></span> Compte de <?php echo $compte->getNomAAfficher(); ?></h4>
                    </div>
                    <div class="col-xs-4 col-sm-3 text-muted text-right">
                        <?php if($modifiable): ?>
                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Modifier <span class="caret"></span></a>
                            <ul class="dropdown-menu text-left">
                                <li<?php echo ($compte->getSOciete()->isSuspendu() || $compte->isSuspendu()) ? ' class="disabled"' : ''; ?>><a href="<?php echo ($compte->getSociete()->isSuspendu() || $compte->isSuspendu()) ? 'javascript:void(0)' : url_for('compte_modification', $compte); ?>">Editer</a></li>
                                <li<?php echo ($compte->getSOciete()->isSuspendu() || $compte->isSuspendu())? ' class="disabled"' : ''; ?>><a href="<?php echo ($compte->getSociete()->isSuspendu() || $compte->isSuspendu())? 'javascript:void(0)' : url_for('compte_switch_statut', array('identifiant' => $compte->identifiant)); ?>">Archiver</a></li>
                                <li<?php echo ($compte->getSOciete()->isSuspendu() || $compte->isActif())? ' class="disabled"' : ''; ?>><a href="<?php echo ($compte->getSociete()->isSuspendu() || $compte->isActif())? 'javascript:void(0)' : url_for('compte_switch_statut', array('identifiant' => $compte->identifiant)); ?>">Activer</a></li>
                                <li><a href="<?php echo url_for('compte_switch_en_alerte', array('identifiant' => $compte->identifiant)); ?>"><?php echo ($compte->exist('en_alerte') && $compte->en_alerte)? 'Retirer alerte' : 'Mettre en alerte' ?></a></li>
                                <li><a onclick='return confirm("Êtes vous sûr de vouloir supprimer cet interlocuteur ?");' href="<?php echo url_for('compte_interlocuteur_delete', array('identifiant' => $compte->identifiant)); ?>">Supprimer</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="panel-body panel-primary-bordered-right">
                <h2>
                	<?php echo $compte->nom_a_afficher; ?>
                	<?php if ($compte->isSuspendu()): ?>
					    <span class="label label-default pull-right" style="padding-top: 0;"><small style="font-weight: inherit; color: inherit;"><?php echo $compte->getStatutLibelle(); ?></small></span>
					<?php endif; ?>
                    <?php if ($compte->exist('en_alerte') && $compte->en_alerte): ?><span class="pull-right">⛔</span><?php endif; ?>
                </h2>
                <hr/>
                <div class="row">
                    <div class="col-xs-5">
                        <div class="row">
                            <?php if ($compte->identifiant): ?>
                                <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">Identifiant&nbsp;:</div>
                                <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo $compte->identifiant; ?></div>
                            <?php endif; ?>
                            <?php if ($compte->fonction): ?>
                                <div style="margin-bottom: 5px;" class="col-xs-4 text-muted">Fonction&nbsp;:</div>
                                <div style="margin-bottom: 5px;" class="col-xs-8"><?php echo $compte->fonction; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-xs-7" style="border-left: 1px solid #eee">
                        <?php include_partial('compte/visualisationAdresse', array('compte' => $compte)); ?>
                    </div>
                </div>
                <?php if($compte->mot_de_passe): ?>
                <hr />
                <h5 style="margin-bottom: 15px; margin-top: 15px;" class="text-muted"><strong>Télédéclaration</strong></h5>
                <?php include_partial('compte/visualisationLogin', array('compte' => $compte)); ?>
                <?php endif; ?>
                <hr />
                <h5 style="margin-bottom: 15px; margin-top: 15px;" class="text-muted"><strong>Informations complémentaires</strong></h5>
                <?php include_partial('compte/visualisationTags', array('compte' => $compte, 'formAjoutGroupe' => $formAjoutGroupe, 'modifiable' => $modifiable)); ?>
                <?php if ($compte->commentaire && $modifiable) : ?>
                <hr />
                <h5 class="text-muted" style="margin-bottom: 15px; margin-top: 0px;"><strong>Commentaire</strong></h5>
                <pre><?php echo html_entity_decode($compte->commentaire); ?></pre>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <?php include_component('societe', 'sidebar', array('societe' => $societe, 'activeObject' => $compte, 'modifiable' => $modifiable)); ?>
    </div>
</div>
