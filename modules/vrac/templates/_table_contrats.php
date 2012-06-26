
<style>
td{padding: 0px 10px;}
</style>
<table>    
    <thead>
        <tr>
            <th>Type</th>
            <th>N° Contrat</th>
            <th>Soussignés</th>   
            <th>Produit</th>
            <th>Vol. com. / Vol. enlv.</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($vracs->rows as $value)
        {   
            $elt = $value->getRawValue()->value;
            $statusColor = statusColor($elt[VracClient::VRAC_VIEW_STATUT]);
            $vracid = preg_replace('/VRAC-/', '', $elt[VracClient::VRAC_VIEW_NUMCONTRAT]);
            $vracid = substr($vracid,0,8)."&nbsp;".substr($vracid,8,  strlen($vracid)-1);
        ?>
        <tr style="<?php echo 'background-color:'.$statusColor.';' ?>" >
              <td><?php echo ($elt[VracClient::VRAC_VIEW_TYPEPRODUIT])? typeProduit($elt[VracClient::VRAC_VIEW_TYPEPRODUIT]) : ''; ?></td>
	      <td><?php echo link_to($vracid, '@vrac_termine?numero_contrat='.$vracid); ?></td>
              <td>
                     <ul>  
                    <li>
                      <?php echo ($elt[VracClient::VRAC_VIEW_VENDEUR_ID])? 
                                    'Vendeur : '.link_to($elt[VracClient::VRAC_VIEW_VENDEUR_NOM],
                                            'vrac/recherche?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_VENDEUR_ID])) 
                                  : ''; ?>
                    </li>
                    <li>
                      <?php echo ($elt[VracClient::VRAC_VIEW_ACHETEUR_ID])?
                                    'Acheteur : '.link_to($elt[VracClient::VRAC_VIEW_ACHETEUR_NOM],
                                            'vrac/recherche?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_ACHETEUR_ID])) 
                                : ''; ?>
                    </li>
                    <li>
                      <?php echo ($elt[VracClient::VRAC_VIEW_MANDATAIRE_ID]) ? 
                                    'Mandataire : '.link_to($elt[VracClient::VRAC_VIEW_MANDATAIRE_NOM], 
                                            'vrac/recherche?identifiant='.preg_replace('/ETABLISSEMENT-/', '', $elt[VracClient::VRAC_VIEW_MANDATAIRE_ID])) 
                                 : ''; ?>
                    </li>
                  </ul>
              </td>              
              <td><?php echo ($elt[VracClient::VRAC_VIEW_PRODUIT_ID])? ConfigurationClient::getCurrent()->get($elt[VracClient::VRAC_VIEW_PRODUIT_ID])->libelleProduit() : ''; ?></td>
              <td><?php echo (isset($elt[VracClient::VRAC_VIEW_VOLCONS]) && isset($elt[VracClient::VRAC_VIEW_VOLENLEVE]))?
                                    $elt[VracClient::VRAC_VIEW_VOLCONS].' / '.$elt[VracClient::VRAC_VIEW_VOLENLEVE]
                                    : ''; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>    