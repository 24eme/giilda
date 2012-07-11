<?php
use_helper('Vrac');
$params = array('etape' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_ETAPE],
                'vendeur' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_VENDEURID],
                'acheteur' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_ACHETEURID],
                'mandataire' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_MANDATAIREID],
                'produit' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_PRODUIT],
                'type' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_TYPE],
                'volume'=>$vrac[VracClient::VRAC_SIMILAIRE_KEY_VOLPROP]);
          

if(!isset($vracs) || !$vracs)
{
    if($params['etape']=='1'){
        $vracs = VracClient::getInstance()->retrieveSimilaryContracts($params);
    }
    else {
        $vracs = VracClient::getInstance()->retrieveSimilaryContractsWithProdTypeVol($params);
    }
    
}

$flagStatut = false;
?>
<div id="contrats_similaires" class="bloc_col">
        <h2>Contrats similaires</h2>
        <div class="contenu">
                <ul id="contrats_similaires_list">
                        <li class="legende_contrat"><span class="statut statut_solde"></span> Soldé <span class="f_right"><span class="statut statut_non-solde"></span> Non soldé</span></li>
                        <li class="separateur"></li>
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
                                $num_contrat = preg_replace('/VRAC-/', '',$elt[VracClient::VRAC_SIMILAIRE_VALUE_NUMCONTRAT]);
                                $volprop = $elt[VracClient::VRAC_SIMILAIRE_VALUE_VOLPROP];
                                $millesime = (is_null($elt[VracClient::VRAC_SIMILAIRE_VALUE_MILLESIME]))? null : $elt[VracClient::VRAC_SIMILAIRE_VALUE_MILLESIME];
                                                                ?>                            
                            <a class="contrat_similaire_num_contrat" target="_blank" href="<?php echo url_for('vrac_visualisation',array('numero_contrat' => $num_contrat)); ?>">
                                <span id="volprop"> <?php echo $volprop; ?></span>&nbsp;-&nbsp;
                                <span id="num_contrat"><?php echo $num_contrat ; ?></span>
                                <?php if($millesime) : ?>
                                    <span id="millesime"><?php echo $millesime ; ?></span>
                                <?php endif; ?>
                                <span class="btn_fleche_ronde"></span>
                            </a>
                        </li>
                        <li class="separateur"></li>
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