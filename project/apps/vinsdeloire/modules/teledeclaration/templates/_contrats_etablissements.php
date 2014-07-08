<?php
use_helper('Vrac');
use_helper('Float');
?>
<h2>
    Espace de <?php echo $societe->raison_sociale; ?>
</h2>
<ul>
    <?php $num_etb = 1; ?>
    <?php foreach ($contratsEtablissements as $etbId => $contratsEtablissement): ?>
        <li>
            <div>
               <?php echo 'Etablissement #'.$num_etb; ?>
                
                <?php include_partial('teledeclaration/contrat_info_etablissement', array('etablissement' => $etablissements[$etbId]->etablissement,'compte' => $compte)); ?>
               
            </div>


            <?php if (count($contratsEtablissement)): ?>
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
                        foreach ($contratsEtablissement as $campagne => $contrats) :
                            foreach ($contrats as $contrat) :
                                if (!is_null($contrat->valide->statut)):
                                    $statusColor = statusColor($contrat->valide->statut);
                                    $vracid = preg_replace('/VRAC-/', '', $contrat->numero_contrat);
                                    ?>
                                    <tr id="<?php echo vrac_get_id($value) ?>" class="<?php echo $statusColor; ?>" >
                                        <td class="type" ><span class="type_<?php echo strtolower($contrat->type_transaction); ?>"><?php echo ($contrat->type_transaction) ? typeProduit($contrat->type_transaction) : ''; ?></span></td>
                                        <td class="num_contrat">
                                            <a href="<?php echo url_for('@vrac_visualisation?numero_contrat=' . $vracid); ?>">
                                            <span style="font-weight: bold;"><?php echo $contrat->numero_archive; ?></span><br> <?php echo preg_replace('/(\d{4})(\d{2})(\d{2}).*/', '$3/$2/$1', $contrat->numero_contrat); ?>
                                            </a>
                                        </td>

                                        <td><?php echo $contrat->produit_libelle; ?></td>
                                        <td class="soussigne">
                                            <ul>  
                                                <?php if($contrat->vendeur_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_VITICULTEUR):?>
                                                <li>
                                                    <span style="font-weight: bold;" >
                                                        Vendeur :
                                                    </span>                                                    
                                                    <?php echo $contrat->vendeur->nom; ?>
                                                </li>
                                                <?php endif; ?>
                                                <?php if($contrat->acheteur_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_NEGOCIANT):?>
                                                <li>
                                                    <span style="font-weight: bold;" >
                                                        Acheteur :
                                                    </span>
                                                    <?php echo $contrat->acheteur->nom; ?>
                                                </li>
                                                 <?php endif; ?>
                                                <?php if($contrat->mandataire_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_COURTIER):?>
                                                <li>                                                    
                                                    <span style="font-weight: bold;" >
                                                        Mandataire :
                                                    </span>
                                                    <?php echo $contrat->mandataire->nom;?>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </td>              
                                        <td>           
                                            <?php echo $contrat->getTeledeclarationStatut(); ?>
                                        </td>
                                        <td>           
                                          <?php if ($contrat->getTeledeclarationStatut() == VracClient::STATUS_TELEDECLARATION_VALIDE): ?>
                                            <a href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $contrat->numero_contrat))?>">
                                                <span id="picto_visualiser">
                                                    Visualiser
                                                </span>
                                            </a>
                                          <?php elseif($contrat->getTeledeclarationStatut() == VracClient::STATUS_TELEDECLARATION_ATTENTE_SIGNATURE): ?>
                                           <a href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $contrat->numero_contrat))?>">
                                                <span id="picto_visualiser">
                                                    Visualiser pour signer
                                                </span>
                                            </a>
                                          <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                endif;
                            endforeach;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p> Pas de contrats </p>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>