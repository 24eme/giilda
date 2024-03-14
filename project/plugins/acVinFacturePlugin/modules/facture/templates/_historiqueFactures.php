<?php
use_helper('Date');
?>
  <?php if ($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
    <a href="<?php echo url_for('compte_teledeclarant_modification') ?>">
      <small class="label label-sm label-default">Voir les infos SEPA</small>
    </a>
  <?php endif ?>
<h2>Historique des factures de <?php echo $societe->getRaisonSociale() ?></h2>
<?php if (count($factures)): ?>
<table style="margin-bottom: 10px;" class="table table-striped">
    <thead>
        <tr>
            <th class="col-xs-1">Type</th>
            <th class="col-xs-1">Document</th>
            <th class="col-xs-1">Numéro</th>
            <th class="col-xs-3">Date de facturation</th>
            <th class="col-xs-1"></th>
            <th class="col-xs-1 text-right">Montant&nbsp;TTC</th>
            <?php if($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
            <th class="col-xs-1 text-right">Montant&nbsp;payé</th>
            <?php endif; ?>
            <th class="col-xs-3"></th>
        </tr>
    </thead>
    <tbody>
        <?php $fc = FactureClient::getInstance(); ?>
        <?php foreach ($factures->getRawValue() as $facture): ?>
            <?php
              $f = $fc->find($facture->id);
              if ($f->getTypeFacture() == 'SV12 Interne' && $sf_user->hasTeledeclaration()) continue;
              $date = $date = format_date($facture->value[FactureSocieteView::VALUE_DATE_FACTURATION], 'dd/MM/yyyy') . ' (créée le ' . $fc->getDateCreation($facture->id) . ')';
            ?>
            <tr>
                <td><?php if ($f->isAvoir()): ?>AVOIR<?php else: ?>FACTURE<?php endif; ?></td>
                <td><?php echo $f->getTypeFacture(); ?></td>
                <td>N°&nbsp;<?php echo $f->numero_piece_comptable ?></td>
                <td><?php echo $date; ?></td>
                <td><?php if($f->isRedressee()): ?><span class="label label-warning">Redressée</span><?php endif;?></td>
                <td class="text-right"><?php echo echoFloat($f->total_ttc); ?>&nbsp;€</td>
                <?php if($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
                  <?php if(FactureConfiguration::getInstance($facture->key[FactureSocieteView::KEYS_INTERPRO])->getPaiementsActif()): ?>
                    <td class="text-right"><?php echo echoFloat($f->getMontantPaiement()); ?>&nbsp;€</td>
                  <?php else: ?>
                    <td class="text-right"></td>
                  <?php endif; ?>
                <?php endif; ?>
                <td class="text-right">
                    <div class="btn-group text-left">
                        <?php if(FactureConfiguration::getInstance($interpro)->getPaiementsActif() && $sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
                          <button type="button" class="btn btn-default btn-default-step btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span>&nbsp;<span class="caret"></span></button>
                          <ul class="dropdown-menu dropdown-menu-right">
                              <?php if ($f->isRedressable()): ?>
                                <li><a onclick="return confirm('Êtes-vous sur de vouloir annuler cette facture en créant un avoir ?');" href="<?php echo url_for("facture_defacturer", array("id" => $f->_id)) ?>"><span class="glyphicon glyphicon-repeat"></span>&nbsp;Défacturer</a></li>
                              <?php else: ?>
                                <li  class="disabled"><a href=""><span class="glyphicon glyphicon-repeat"></span>&nbsp;Défacturer</a></li>
                              <?php endif; ?>
                              <?php if ($f->isAvoir()): ?>
                                <li  class="disabled"><a href="">Saisir / modifier les paiements</a></li>
                              <?php else: ?>
                                <li><a href="<?php echo url_for("facture_paiements", array("id" => $f->_id)) ?>">Saisir / modifier les paiements</a></li>
                              <?php endif; ?>
                          </ul>
                        <?php else: ?>
                          <?php if ($f->isRedressable() && $sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
                          <a onclick="return confirm('Êtes-vous sur de vouloir annuler cette facture en créant un avoir ?');" href="<?php echo url_for("facture_defacturer", array("id" => $f->_id)) ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-repeat"></span>&nbsp;Défacturer</a>
                          <?php endif; ?>
                        <?php endif; ?>
                    <a href="<?php echo url_for("facture_pdf", array("id" => $f->_id)) ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-file"></span>&nbsp;Visualiser</a>
                    <?php if($sf_user->hasCredential(AppUser::CREDENTIAL_ADMIN)): ?>
                    &nbsp;&nbsp;<span style="opacity: 0.8; top: -9px; left: 3px;" data-toggle="tooltip" title="La facture a été téléchargée par l'opérateur" class="glyphicon glyphicon-eye-open <?php if(!$f->isTelechargee()): ?>invisible<?php endif; ?>"></span>
                    <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p class="text-center text-muted"><i>Aucune Facture</i></p>
<?php endif; ?>
<?php if(count($societe->getSocietesLieesIds()) >= 2): ?>
<p>Voir l'historique de : <?php foreach($societe->getSocietesLieesIds() as $societeLieeId): ?>
  <?php $societeLiee = SocieteClient::getInstance()->find($societeLieeId); ?>
  <?php if(!$societeLiee || $societeLiee->_id == $societe->_id): continue; endif; ?>
  <a href="<?php echo url_for('facture_societe', $societeLiee) ?>"><span class="glyphicon glyphicon-link"></span> <?php echo $societeLiee->raison_sociale ?></a>
<?php endforeach; ?></p>
<?php endif; ?>
