<?php
use_helper('Float');
?>
<?php include_partial('facture/preTemplate'); ?>

<ol class="breadcrumb">
    <li class="visited"><a href="<?php if($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?><?php echo url_for('facture') ?><?php endif; ?>">Factures</a></li>
    <li class="active"><a href="<?php echo url_for('facture_societe', $societe) ?>" class="active"><?php echo $societe->raison_sociale ?> (<?php echo $societe->identifiant ?>)</a></li>
</ol>

<div class="row">
    <?php if($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
    <div class="col-xs-12" id="formEtablissementChoice">
        <?php include_component('facture', 'chooseSociete', array('identifiant' => $societe->identifiant)); ?>
    </div>
    <?php endif; ?>
    <div class="col-xs-12">
        <?php include_partial('historiqueFactures', array('societe' => $societe, 'factures' => $factures, 'interpro' => $interproFacturable)); ?>
          <?php if (MandatSepaConfiguration::getInstance()->isActive()): ?>
            <hr />
                <h2>Prélèvement SEPA</h2>
                <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="titre_principal">Vos coordonnées bancaires</h4>
                    </div>
                    <div class="panel-body">
                    <?php if ($mandatSepa): ?>
                        <div class="row">
                          <div class="col-xs-2 text-right">
                              <label>Statut :</label>
                          </div>
                          <div class="col-xs-6 text-left<?php if(!$mandatSepa->is_signe): ?> text-danger<?php endif; ?>">
                              <?php echo $mandatSepa->getStatut(); ?>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-2 text-right">
                              <label>IBAN :</label>
                          </div>
                          <div class="col-xs-6 text-left">
                              <?php echo chunk_split($mandatSepa->debiteur->iban, 4, ' '); ?>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-2 text-right">
                              <label>BIC :</label>
                          </div>
                          <div class="col-xs-6 text-left">
                              <?php echo $mandatSepa->debiteur->bic; ?>
                          </div>
                        </div>

                        <?php if (MandatSepaConfiguration::getInstance()->isAccessibleTeledeclaration()): ?>
                          <div class="row">
                            <div class="col-xs-3 text-right">
                                <label>Mandat de prélèvement SEPA :</label>
                            </div>
                            <div class="col-xs-6 text-left">
                                <a href="<?php echo url_for('mandatsepa_pdf', $mandatSepa) ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-file"></span>&nbsp;Télécharger le document</a>
                            </div>
                          </div>
                          <?php if (!$mandatSepa->is_telecharge && false): ?>
                            <?php include_partial('mandatsepa/popupIncitationSignatureMandat', array('mandatSepa' => $mandatSepa)); ?>
                          <?php endif; ?>
                        <?php endif ?>
                    <?php else: ?>
                      <div class="row">
                        <div class="col-xs-6 col-xs-offset-3 text-center">
                          <p>Vous n'avez pas encore saisi de coordonnées bancaires</p>
                        </div>
                        <div class="col-xs-12">
                              <a href="<?php echo url_for('mandatsepa_modification', ['identifiant' => $societe->getIdentifiant()]) ?>" class=" btn btn-warning modifier" style="cursor: pointer; float: right;">Saisir vos coordonnées bancaires</a>
                        </div>
                      </div>
                    <?php endif; ?>
                    </div>
              </div>
              <hr />
          <?php endif; ?>

        <?php if($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
        <?php
        try {
            $no_region = ! count($societe->getRegionsViticoles());
            include_partial('facture/mouvements', array('mouvements' => $mouvements, 'societe' => $societe));
        }catch(Exception $e) {
            echo "<p><i>Societé n'ayant pas de région (ou hors région), impossible d'afficher ses éventuels mouvements passés.</i></p>";
        }
        ?>
        <?php endif; ?>
    </div>
</div>
<?php include_partial('facture/postTemplate'); ?>
