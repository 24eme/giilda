<?php
$maxlimit = (isset($limit) && $limit)? $limit : null;
$cpt = 0;
?>
<div class="row">
  <div class="col-xs-12">
<?php if (count($contrats)): ?>
        <ul class="list-group">
          <li class="list-group-item">
                <div class="row">
                    <div class="col-xs-2">Type</div>
                    <div class="col-xs-2">N° - Date</div>
                    <div class="col-xs-3">Produit</div>
                    <div class="col-xs-3">Soussignés</div>
                    <div class="col-xs-2">Statut/Actions</div>
                </div>
            </li>
                <?php
                foreach ($contrats as $contrat):
                    if(!is_null($maxlimit) && ($cpt >= $maxlimit)){
                        break;
                    }
                    $statut = $contrat->value[VracClient::VRAC_VIEW_STATUT];
                    if (!is_null($statut)):
                        $statusColor = 'default';
                        if($contrat->value[VracClient::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE){
                          $statusColor = 'warning';
                        }elseif($contrat->value[VracClient::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_VISE){
                          $statusColor = 'success';
                        }elseif($contrat->value[VracClient::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_ANNULE){
                          $statusColor = 'danger';
                        }
                        $vracid = $contrat->value[VracClient::VRAC_VIEW_NUMCONTRAT];

                        $typeProduit = $contrat->value[VracClient::VRAC_VIEW_PRODUIT_LIBELLE];
                        $numero_archive = $contrat->value[VracClient::VRAC_VIEW_NUMARCHIVE];
                        $produit_libelle = $contrat->value[VracClient::VRAC_VIEW_VOLENLEVE];

                        $vendeur_identifiant = $contrat->value[VracClient::VRAC_VIEW_VENDEUR_ID];
                        $vendeur_nom = $contrat->value[VracClient::VRAC_VIEW_VENDEUR_NOM];

                        $acheteur_identifiant = $contrat->value[VracClient::VRAC_VIEW_ACHETEUR_ID];
                        $acheteur_nom = $contrat->value[VracClient::VRAC_VIEW_ACHETEUR_NOM];

                        $mandataire_identifiant = $contrat->value[VracClient::VRAC_VIEW_MANDATAIRE_ID];
                        $mandataire_nom = $contrat->value[VracClient::VRAC_VIEW_MANDATAIRE_NOM];

                        $signature_vendeur = (isset($contrat->value[VracClient::VRAC_VIEW_SIGNATUREVENDEUR]))? $contrat->value[VracClient::VRAC_VIEW_SIGNATUREVENDEUR] : null;
                        $signature_acheteur = (isset($contrat->value[VracClient::VRAC_VIEW_SIGNATUREACHETEUR]))? $contrat->value[VracClient::VRAC_VIEW_SIGNATUREACHETEUR] : null;
                        $signature_courtier = (isset($contrat->value[VracClient::VRAC_VIEW_SIGNATURECOURTIER]))? $contrat->value[VracClient::VRAC_VIEW_SIGNATURECOURTIER] : null;
                        $createur_identifiant = $contrat->value[VracSoussigneIdentifiantView::VRAC_VIEW_VALUE_CREATEUR_IDENTIFIANT];

                        $toBeSigned = VracClient::getInstance()->toBeSignedBySociete($statut, $societe, $signature_vendeur, $signature_acheteur, $signature_courtier);
                        $cpt++;
                        ?>
                          <li id="<?php echo 'vrac_'.$vracid; ?>" class="list-group-item list-group-item-<?php echo $statusColor; ?>">
                            <div class="row" >
                            <div class="col-xs-2 type">
                              <span class="<?php echo typeProduitIcon($typeProduit) ; ?>" style="font-size: 32px;"></span>
                            </div>
                            <div class="col-xs-2 num_contrat">
                                <a href="<?php echo url_for('@vrac_visualisation?numero_contrat=' . $vracid); ?>">
                                    <span style="font-weight: bold;"><?php echo $numero_archive; ?></span><br> <?php echo dateFirstSignatureFromView($signature_vendeur,$signature_acheteur,$signature_courtier,$contrat); ?>
                                </a>
                            </div>

                            <div class="col-xs-3 produit"><?php echo ($produit_libelle)? $produit_libelle : '-'; ?></div>
                            <div class="col-xs-3 soussigne">
                                <ul class="list-unstyled">
                                    <?php if ($vendeur_identifiant): ?>
                                    <li class="<?php echo getPictoSignature($societe, $contrat, 'Vendeur'); ?>">
                                            <span style="font-weight: bold;">
                                                Vendeur :
                                            </span>
                                            <?php echo $vendeur_nom; ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($acheteur_identifiant): ?>
                                        <li class="<?php echo getPictoSignature($societe, $contrat, 'Acheteur'); ?>">
                                            <span style="font-weight: bold;">
                                                Acheteur :
                                            </span>
                                            <?php echo $acheteur_nom; ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($mandataire_identifiant): ?>
                                        <li class="<?php echo getPictoSignature($societe, $contrat, 'Courtier'); ?>">
                                            <span style="font-weight: bold;">
                                                Courtier :
                                            </span>
                                            <?php echo $mandataire_nom; ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="col-xs-2 statut ">
                                <p>
                                    <?php echo VracClient::$statuts_labels_teledeclaration[$statut]; ?>
                                </p>

                                <?php if (($statut == VracClient::STATUS_CONTRAT_NONSOLDE) || ($statut == VracClient::STATUS_CONTRAT_SOLDE)): ?>
                                    <a class="btn btn-default" href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $vracid)) ?>">
                                        <span class="glyphicon glyphicon-eye-open"></span>&nbsp;Visualiser
                                    </a>
                                 <?php  elseif ($statut == VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE): ?>
                                    <a class="btn btn-default" href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $vracid)) ?>">
                                       <?php  if ($toBeSigned) : ?>
                                        Visualiser pour signer
                                        <?php  else : ?>
                                        <span class="glyphicon glyphicon-eye-open"></span>&nbsp;Visualiser
                                        <?php  endif; ?>
                                    </a>
                                <?php elseif ($statut == VracClient::STATUS_CONTRAT_BROUILLON && ($societe->identifiant == substr($createur_identifiant, 0,6))): ?>
                                     <a class="btn btn-default" href="<?php echo url_for('vrac_redirect_saisie', array('numero_contrat' => $vracid)) ?>">
                                         <span class="glyphicon glyphicon-pencil"></span>&nbsp;Continuer
                                    </a>
                                <?php endif;  ?>
                            </div>
                            </div>
                        </li>
                        <?php
                    endif;
                endforeach;
                ?>
            </ul>

    <?php else: ?>
           Pas de contrats
    <?php endif; ?>
</div>
</div>
