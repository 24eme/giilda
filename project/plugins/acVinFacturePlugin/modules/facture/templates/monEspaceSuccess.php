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
        <?php if($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
          <?php if (MandatSepaConfiguration::getInstance()->isActive()): ?>
            <hr />
                <h2>Prélèvement SEPA</h2>
                <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="titre_principal">Coordonnées bancaires</h4>
                    </div>
                    <div class="panel-body">

                    <div class="col-xs-12">
                      <h4>Vos coordonnées bancaires : </h4>
                    </div>
                    <?php if ($mandatSepa): ?>
                      <div class="col-xs-8">
                        <div class="row">
                          <div class="col-xs-6 text-right">
                              <label>IBAN :</label>
                          </div>
                          <div class="col-xs-6 text-left">
                              <?php echo $mandatSepa->debiteur->iban; ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-6 text-right">
                              <label>BIC :</label>
                          </div>
                          <div class="col-xs-6 text-left">
                              <?php echo $mandatSepa->debiteur->bic; ?>
                          </div>
                        </div>
                        <div class="row">&nbsp;</div>

                        <?php if (MandatSepaConfiguration::getInstance()->hasPDF()): ?>
                        <div class="row">
                          <div class="col-xs-6 text-right">
                              <label>Mandat de prélèvement SEPA :</label>
                          </div>
                          <div class="col-xs-6 text-left">
                              <a href="<?php echo url_for('mandatsepa_pdf', $mandatSepa) ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-file"></span>&nbsp;Télécharger le document</a>
                          </div>
                        </div>
                        <?php endif ?>
                        <div class="row">
                          <div class="col-xs-6 text-right">
                              <label>Statut :</label>
                          </div>
                          <div class="col-xs-6 text-left<?php if(!$mandatSepa->is_signe): ?> text-danger<?php endif; ?>">
                              <?php echo $mandatSepa->getStatut(); ?>
                          </div>
                        </div>
                      </div>
                      <?php if (!$mandatSepa->is_telecharge && false): ?>
                      <?php include_partial('mandatsepa/popupIncitationSignatureMandat', array('mandatSepa' => $mandatSepa)); ?>
                      <?php endif; ?>
                    <?php else: ?>
                      <div class="col-xs-8">
                        <div class="row">
                          <div class="col-xs-6 text-right"></div>
                          <div class="col-xs-6 text-left">
                            <p>Vous n'avez pas saisi de coordonnées bancaires</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12">
                            <a href="<?php echo url_for('compte_teledeclarant_coordonnees_bancaires') ?>" class=" btn btn-warning modifier" style="cursor: pointer; float: right;">Saisir vos coordonnées bancaires</a>
                      </div>
                    <?php endif; ?>
                    </div>
              </div>
          <?php endif; ?>
         <hr />
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
