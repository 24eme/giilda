<?php use_helper('Float'); ?>
<?php use_helper('SV12'); ?>

<?php //include_partial('global/hamzaStyle', array('mots' => contrat_get_words($contrats), 'table_selector' => '#table_contrats')) ?>

<fieldset>
        <table id="table_contrats" class="table_recap">
        <thead>
        <tr>
            <th>Viticulteur</th>
            <th>Produit</th>                  
            <th>Contrat</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($contrats as $contrat) : ?> 
            <tr id="<?php echo contrat_get_id($contrat) ?>">
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