<?php
use_helper('Vrac');
use_helper('Float');
?>
<section id="principal">
<h2 class="titre_societe">
    Espace de <?php echo $societe->raison_sociale; ?>
</h2>
<ul>
    <?php $num_etb = 1; ?>
    <?php foreach ($contratsEtablissements as $etbId => $contratsEtablissement): ?>
        <li>
            <div>
                <?php echo 'Etablissement #'.$num_etb; ?>
                <?php $etablissement = $etablissements[$etbId]->etablissement; ?>

                <div id="etablissement_<?php echo $etablissement->identifiant; ?>" class="">
                    <h3><?php echo $etablissement->nom; ?></h3>
                    <ul id="liste_statuts_nb" class="">    
                        
                    </ul>
                    <div id="num_etb">
                        N° <?php echo $etablissement->identifiant; ?>
                    </div>
                    <div id="cp_etb">
                        Code postal: <?php echo $etablissement->siege->code_postal; ?>
                    </div>
                    <div id="commune_etb">
                        Commune: <?php echo $etablissement->siege->commune; ?>
                    </div>
                </div>
                <!--<div id="etablissements_vracs_button">    
                    <a href="<?php echo url_for('vrac_creation',array('identifiant' => $etablissement->identifiant)) ?>">Nouveau</a>
                </div>-->

                <div id="ligne_btn" class="txt_droite">
                    <a class="btn_orange btn_majeur" href="<?php echo url_for('vrac_nouveau', array('etablissement' => $etbId)); ?>">
                        Nouveau contrat
                    </a>
                </div>
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
                                        <td class="type"><span class="type_<?php echo strtolower($contrat->type_transaction); ?>"><?php echo ($contrat->type_transaction) ? typeProduit($contrat->type_transaction) : ''; ?></span></td>
                                        <td class="num_contrat">
                                            <a href="<?php echo url_for('@vrac_visualisation?numero_contrat=' . $vracid); ?>">
                                            <span style="font-weight: bold;"><?php echo $contrat->numero_archive; ?></span><br> <?php echo preg_replace('/(\d{4})(\d{2})(\d{2}).*/', '$3/$2/$1', $contrat->numero_contrat); ?>
                                            </a>
                                        </td>

                                        <td class="produit"><?php echo $contrat->produit_libelle; ?></td>
                                        <td class="soussigne">
                                            <ul>  
                                                <?php if($contrat->vendeur_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_VITICULTEUR):?>
                                                <li class="contrat_signe_moi">
                                                    <span style="font-weight: bold;">
                                                        Vendeur :
                                                    </span>                                                    
                                                    <?php echo $contrat->vendeur->nom; ?>
                                                </li>
                                                <?php endif; ?>
                                                <?php if($contrat->acheteur_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_NEGOCIANT):?>
                                                <li class="contrat_attente">
                                                    <span style="font-weight: bold;">
                                                        Acheteur :
                                                    </span>
                                                    <?php echo $contrat->acheteur->nom; ?>
                                                </li>
                                                 <?php endif; ?>
                                                <?php if($contrat->mandataire_identifiant && $societe->type_societe != SocieteClient::SUB_TYPE_COURTIER):?>
                                                <li>                                                    
                                                    <span style="font-weight: bold;">
                                                        Mandataire :
                                                    </span>
                                                    <?php echo $contrat->mandataire->nom;?>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </td>              
                                        <td class="statut">           
                                            <?php echo $contrat->getTeledeclarationStatut(); ?>
                                        </td>
                                        <td class="actions">           
                                          <?php if ($contrat->getTeledeclarationStatut() == VracClient::STATUS_VALIDE): ?>
                                            <a class="visualiser_contrat" href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $contrat->numero_contrat))?>">
                                                Visualiser
                                            </a>
                                          <?php elseif($contrat->getTeledeclarationStatut() == VracClient::STATUS_ATTENTE_SIGNATURE): ?>
                                           <a class="visualiser_contrat" href="<?php echo url_for('vrac_visualisation', array('numero_contrat' => $contrat->numero_contrat))?>">
                                                Visualiser pour signer
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

<div id="ligne_btn" class="txt_droite">
    <a class="btn_vert btn_majeur" href="<?php echo url_for('annuaire', array('identifiant' => $etablissements[$etbId]->etablissement->identifiant)); ?>">
        Annuaire
    </a>
</div>
</section>