<?php
use_helper('Vrac');
$params = array('etape' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_ETAPE],
                'vendeur' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_VENDEURID],
                'acheteur' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_ACHETEURID],
                'mandataire' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_MANDATAIREID],
                'produit' => $vrac[VracClient::VRAC_SIMILAIRE_KEY_PRODUIT],
                'volume'=>$vrac[VracClient::VRAC_SIMILAIRE_KEY_VOLPROP]);

$vracs = VracClient::getInstance()->retrieveSimilaryContracts($params);
?>
<div id="contrats_similaires" class="bloc_col">
        <h2>Contrats similaire</h2>
        <div class="contenu">
                <p>
                    <img src="" alt="Soldé"/>
                    <span>Soldé</span>
                    <img src="" alt="Non-Soldé"/>
                    <span>Non-Soldé</span>
                </p>

                <ul id="contrats_similaires_list">
                      <?php 
                      foreach ($vracs->rows as $value) 
                      {
                          $elt =$value->value;
                          //$statusColor = statusColor($elt[VracClient::VRAC_VIEW_STATUT]);
                          if(($elt[VracClient::VRAC_SIMILAIRE_VALUE_NUMCONTRAT]!=$vrac['_id'])
                                 && $elt[VracClient::VRAC_SIMILAIRE_VALUE_STATUT]!=null)
                                  
                          {
                      ?>
                      <li>
                          <span class="contrat_similaire_solde">
                            <?php 
                            echo $elt[VracClient::VRAC_SIMILAIRE_VALUE_STATUT];
                            ?>    
                          </span>
                          <span class="contrat_similaire_num_contrat">
                            <?php 
                            echo preg_replace('/VRAC-/', '', $elt[VracClient::VRAC_SIMILAIRE_VALUE_NUMCONTRAT]);
                            ?>    
                          </span>
                      </li>
                      <?php
                          }
                      }
                      ?>
                </ul>
        </div>
</div>