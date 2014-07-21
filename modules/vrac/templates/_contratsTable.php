    <?php if (count($contrats)): ?>
        <table id="table_contrats" class="table_recap">    
            <thead>
                <tr>
                    <th class="type">Type</th>
                    <th>N° - Date</th>
                    <th>Produit</th>
                    <th>Soussignés</th>   
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($contrats as $contrat):
                            if (!is_null($contrat->valide->statut)):
                                $statusColor = statusColor($contrat->valide->statut);
                                $vracid = preg_replace('/VRAC-/', '', $contrat->numero_contrat);
                                ?>
                                <tr id="<?php echo 'vrac_'.$vracid; ?>" class="<?php echo $statusColor; ?>" >
                                    <td class="type"><span class="type_<?php echo strtolower($contrat->type_transaction); ?>"><?php echo ($contrat->type_transaction) ? typeProduit($contrat->type_transaction) : ''; ?></span></td>
                                    <td class="num_contrat">
                                        <a href="<?php echo url_for('@vrac_visualisation?numero_contrat=' . $vracid); ?>">
                                            <span style="font-weight: bold;"><?php echo $contrat->numero_archive; ?></span><br> <?php echo preg_replace('/(\d{4})(\d{2})(\d{2}).*/', '$3/$2/$1', $contrat->numero_contrat); ?>
                                        </a>
                                    </td>

                                    <td class="produit"><?php echo $contrat->produit_libelle; ?></td>
                                    <td class="soussigne">
                                        <ul>  
                                            <?php if ($contrat->vendeur_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_VITICULTEUR): ?>
                                            <li class="<?php echoPictoSignature($contrat, 'Vendeur'); ?>">
                                                    <span style="font-weight: bold;">
                                                        Vendeur :
                                                    </span>                                                    
                                                    <?php echo $contrat->vendeur->nom; ?>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($contrat->acheteur_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_NEGOCIANT): ?>
                                                <li class="<?php echoPictoSignature($contrat, 'Acheteur'); ?>">
                                                    <span style="font-weight: bold;">
                                                        Acheteur :
                                                    </span>
                                                    <?php echo $contrat->acheteur->nom; ?>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($contrat->mandataire_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_COURTIER): ?>
                                                <li class="<?php echoPictoSignature($contrat, 'Courtier'); ?>">                                                    
                                                    <span style="font-weight: bold;">
                                                        Mandataire :
                                                    </span>
                                                    <?php echo $contrat->mandataire->nom; ?>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </td>              
                                    <td class="statut">           
                                        <?php echo $contrat->getTeledeclarationStatut(); ?>
                                    </td>
                                    <td class="actions">           
                                        <?php if ($contrat->getTeledeclarationStatut() == VracClient::STATUS_CONTRAT_VALIDE): ?>
                                            <a class="visualiser_contrat" href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $contrat->numero_contrat)) ?>">
                                                Visualiser
                                            </a>
                                        <?php elseif ($contrat->getTeledeclarationStatut() == VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE): ?>
                                            <a class="visualiser_contrat" href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $contrat->numero_contrat)) ?>">
                                                Visualiser pour signer
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
