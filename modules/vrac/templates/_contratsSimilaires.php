<?php
use_helper('Vrac');
$params = array('etape' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_ETAPE],
                'vendeur' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_VENDEURID],
                'acheteur' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_ACHETEURID],
                'mandataire' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_MANDATAIREID],
                'produit' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_PRODUIT],
                'type' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_TYPE],
                'volume'=>$vrac[VracClient::VRAC_SIMILAIRE_KEY_VOLPROP]);

$vracs = (!isset($vracs) || !$vracs)? VracClient::getInstance()->retrieveSimilaryContracts($params) : $vracs;
$flagStatut = false;  
?>
<div id="contrats_similaires" class="bloc_col">
        <h2>Contrats similaire</h2>
        <div class="contenu">
                <ul id="contrats_similaires_list">
                        <?php 
                                     
                        foreach ($vracs->rows as $value) 
                        {                           
                            $elt =$value->value;
                            $statusColor = statusColor($elt[VracClient::VRAC_SIMILAIRE_VALUE_STATUT]);
                            //$statusColor = statusColor($elt[VracClient::VRAC_VIEW_STATUT]);
                            if(($elt[VracClient::VRAC_SIMILAIRE_VALUE_NUMCONTRAT]!=$vrac['_id'])
                                    && $elt[VracClient::VRAC_SIMILAIRE_VALUE_STATUT]!=null)

                            {
                                $flagStatut = true;
                        ?>
                        <li>
                            <span class="statut <?php echo $statusColor; ?>"></span>                         
                                <?php 
                            // echo $elt[VracClient::VRAC_SIMILAIRE_VALUE_STATUT];
                                $num_contrat = preg_replace('/VRAC-/', '', $elt[VracClient::VRAC_SIMILAIRE_VALUE_NUMCONTRAT]);
                                ?>    
                                <span class="contrat_similaire_num_contrat">
                                    <?php 
                                    echo link_to($num_contrat, '@vrac_termine?numero_contrat='.$num_contrat);
                                    ?>    
                                </span>
                        </li>
                        <?php
                            }
                        }
                        ?>
                </ul>
            <?php
                if($vracs===FALSE || !$flagStatut || count($vracs->rows)==0)
                {
                ?>
                <span>Il n'existe aucun contrat similaire</span>
                <?php
                }
            ?>
        </div>
</div>