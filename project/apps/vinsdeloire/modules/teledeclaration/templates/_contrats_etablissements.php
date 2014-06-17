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
            </div>
            <?php if (count($contratsEtablissement)): ?>
                <table id="table_contrats" class="table_recap">    
                    <thead>
                        <tr>
                            <th class="type">Type</th>
                            <th>N° Contrat</th>
                            <th>Soussignés</th>   
                            <th>Produit</th>
                            <th>Vol. enlevé. / Vol. prop.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($contratsEtablissement as $campagne => $contrats) :
                            foreach ($contrats as $contrat) :
                                $elt = $contrat->value;
                                if (!is_null($elt[VracClient::VRAC_VIEW_STATUT])):
                                    $statusColor = statusColor($elt[VracClient::VRAC_VIEW_STATUT]);
                                    $vracid = preg_replace('/VRAC-/', '', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]);
                                    ?>
                                    <tr id="<?php echo vrac_get_id($value) ?>" class="<?php echo $statusColor; ?>" >
                                        <td class="type" ><span class="type_<?php echo strtolower($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]); ?>"><?php echo ($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]) ? typeProduit($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]) : ''; ?></span></td>
                                        <td class="num_contrat"><?php echo link_to($elt[VracClient::VRAC_VIEW_NUMARCHIVE] . '&nbsp;(' . preg_replace('/(\d{4})(\d{2})(\d{2}).*/', '$3/$2/$1', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]) . ')', '@vrac_visualisation?numero_contrat=' . $vracid); ?></td>

                                        <td class="soussigne">
                                            <ul>  
                                                <li>
                                                    <?php
                                                    echo ($elt[VracClient::VRAC_VIEW_VENDEUR_ID]) ?
                                                            'Vendeur : ' . link_to($elt[VracClient::VRAC_VIEW_VENDEUR_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_VENDEUR_ID])) : '';
                                                    ?>
                                                </li>
                                                <li>
                                                    <?php
                                                    echo ($elt[VracClient::VRAC_VIEW_ACHETEUR_ID]) ?
                                                            'Acheteur : ' . link_to($elt[VracClient::VRAC_VIEW_ACHETEUR_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_ACHETEUR_ID])) : '';
                                                    ?>
                                                </li>
                                                <li>
                                                    <?php
                                                    echo ($elt[VracClient::VRAC_VIEW_MANDATAIRE_ID]) ?
                                                            'Mandataire : ' . link_to($elt[VracClient::VRAC_VIEW_MANDATAIRE_NOM], 'vrac/recherche?identifiant=' . preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_MANDATAIRE_ID])) : '';
                                                    ?>
                                                </li>
                                            </ul>
                                        </td>              
                                        <td><?php echo $elt[VracClient::VRAC_VIEW_PRODUIT_LIBELLE]; ?></td>
                                        <td>           
                                            <?php
                                            if (isset($elt[VracClient::VRAC_VIEW_VOLENLEVE]))
                                                echoFloat($elt[VracClient::VRAC_VIEW_VOLENLEVE]);
                                            else
                                                echo '0.00';
                                            echo '&nbsp;/&nbsp;';
                                            if (isset($elt[VracClient::VRAC_VIEW_VOLPROP]))
                                                echoFloat($elt[VracClient::VRAC_VIEW_VOLPROP]);
                                            else
                                                echo '0.00';
                                            ?>
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