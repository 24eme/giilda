<?php 
$maxlimit = (isset($limit) && $limit)? $limit : null;
$cpt = 0;
?>    

<?php if (count($contrats)): ?>
        <table id="table_contrats" class="table_recap">    
            <thead>
                <tr>
                    <th class="type">Type</th>
                    <th>N° - Date</th>
                    <th>Produit</th>
                    <th>Soussignés</th>   
                    <th>Statut/Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($contrats as $contrat):
                    if(!is_null($maxlimit) && ($cpt >= $maxlimit)){
                        break;
                    }
                    $statut = $contrat->value[VracClient::VRAC_VIEW_STATUT];
                    if (!is_null($statut)):
                        $statusColor = statusColor($contrat->value[VracClient::VRAC_VIEW_STATUT]);
                        $vracid = $contrat->value[VracClient::VRAC_VIEW_NUMCONTRAT];
                        
                        $typeProduit = $contrat->value[VracClient::VRAC_VIEW_TYPEPRODUIT];
                        $numero_archive = $contrat->value[VracClient::VRAC_VIEW_NUMARCHIVE];
                        $produit_libelle = $contrat->value[VracClient::VRAC_VIEW_PRODUIT_LIBELLE];
                        
                        $vendeur_identifiant = $contrat->value[VracClient::VRAC_VIEW_VENDEUR_ID];
                        $vendeur_nom = $contrat->value[VracClient::VRAC_VIEW_VENDEUR_NOM];
                        
                        $acheteur_identifiant = $contrat->value[VracClient::VRAC_VIEW_ACHETEUR_ID];
                        $acheteur_nom = $contrat->value[VracClient::VRAC_VIEW_ACHETEUR_NOM];
                        
                        $mandataire_identifiant = $contrat->value[VracClient::VRAC_VIEW_MANDATAIRE_ID];
                        $mandataire_nom = $contrat->value[VracClient::VRAC_VIEW_MANDATAIRE_NOM];
                        
                        $signature_vendeur = (isset($contrat->value[VracClient::VRAC_VIEW_SIGNATUREVENDEUR]))? $contrat->value[VracClient::VRAC_VIEW_SIGNATUREVENDEUR] : null;
                        $signature_acheteur = (isset($contrat->value[VracClient::VRAC_VIEW_SIGNATUREACHETEUR]))? $contrat->value[VracClient::VRAC_VIEW_SIGNATUREACHETEUR] : null;
                        $signature_courtier = (isset($contrat->value[VracClient::VRAC_VIEW_SIGNATURECOURTIER]))? $contrat->value[VracClient::VRAC_VIEW_SIGNATURECOURTIER] : null;
                        $createur_identifiant = $contrat->value[VracClient::VRAC_VIEW_CREATEURIDENTIFANT];
                        
                        $toBeSigned = VracClient::getInstance()->toBeSignedBySociete($statut, $societe, $signature_vendeur, $signature_acheteur, $signature_courtier);
                        $cpt++;
                        ?>
                        <tr id="<?php echo 'vrac_'.$vracid; ?>" class="<?php echo $statusColor; ?>" >
                            <td class="type"><span class="type_<?php echo strtolower($typeProduit); ?>"><?php echo ($typeProduit) ? typeProduit($typeProduit) : '-'; ?></span></td>
                            <td class="num_contrat">
                                <a href="<?php echo url_for('@vrac_visualisation?numero_contrat=' . $vracid); ?>">
                                    <span style="font-weight: bold;"><?php echo $numero_archive; ?></span><br> <?php echo preg_replace('/(\d{4})(\d{2})(\d{2}).*/', '$3/$2/$1', $vracid); ?>
                                </a>
                            </td>

                            <td class="produit"><?php echo ($produit_libelle)? $produit_libelle : '-'; ?></td>
                            <td class="soussigne">
                                <ul>  
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
                            </td>              
                            <td class="statut">           
                                <p>
                                    <?php echo VracClient::$statuts_labels_teledeclaration[$statut]; ?>
                                </p> 

                                <?php if (($statut == VracClient::STATUS_CONTRAT_NONSOLDE) || ($statut == VracClient::STATUS_CONTRAT_SOLDE)): ?>
                                    <a class="liens_contrat_teledeclaration" href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $vracid)) ?>">
                                        Visualiser
                                    </a>
                                 <?php  elseif ($statut == VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE): ?>
                                    <a class="liens_contrat_teledeclaration" href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $vracid)) ?>">
                                       <?php  if ($toBeSigned) : ?>
                                        Visualiser pour signer
                                        <?php  else : ?>
                                        Visualiser
                                        <?php  endif; ?>
                                    </a>
                                <?php elseif ($statut == VracClient::STATUS_CONTRAT_BROUILLON && ($societe->identifiant == substr($createur_identifiant, 0,6))): ?> 
                                     <a class="liens_contrat_teledeclaration" href="<?php echo url_for('vrac_redirect_saisie', array('numero_contrat' => $vracid)) ?>">
                                         Continuer Brouillon
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    endif;
                endforeach;
                ?>
            </tbody>
        </table>
    <?php else: ?>
        <p> Pas de contrats </p>
    <?php endif; ?>
