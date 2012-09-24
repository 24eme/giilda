<?php use_helper('Float'); ?>
<fieldset>
        <table class="table_recap">
        <thead>
        <tr>
            <th>Viticulteur</th>
            <th>Produit</th>                  
            <th>Contrat</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($contrats as $contrat) : ?> 
            <tr>
                <td><?php echo $contrat->vendeur_nom.' ('.$contrat->vendeur_identifiant.')'; ?></td>
                <td><?php echo $contrat->produit_libelle; ?></td>   
                <td>
                    <a href="<?php echo url_for(array('sf_route' => 'vrac_visualisation', 'numero_contrat' => $contrat->contrat_numero)) ?>"><?php echo VracClient::getInstance()->getLibelleFromId($contrat->contrat_numero, '&nbsp;') ?></a>
                    <?php echo sprintf('(%s, %s hl)', $contrat->contrat_type, $contrat->volume_prop); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table> 
</fieldset>