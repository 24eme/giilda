<h2>Historique des factures</h2>
<fieldset>
    <table class="table_recap">
        <thead>
            <tr>
                <th>Date</th>
                <th>DRM liées</th>
                <th>Prix TTC</th>
                <th>Défacturer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($factures->getRawValue() as $facture) :
                ?>
                <tr>
                    <td><?php echo link_to($facture->value[0], array('sf_route' => 'facture_pdf', 'identifiant' => str_replace('ETABLISSEMENT-', '', $facture->key[0]), 'factureid' => str_replace('FACTURE-' . $etablissement->identifiant . '-', '', $facture->key[1]))); ?></td>
                    <td><?php foreach ($facture->value[1] as $drmid => $drmlibelle) {
                echo link_to($drmlibelle, 'drm_redirect_to_visualisation', array('identifiant_drm' => $drmid)) . "<br/>";
            }; ?></td>
                    <td><?php echoFloat($facture->value[2]); ?>&nbsp;€</td>
                    <td><?php echo link_to('défacturer les mouvements', '@defacturer?identifiant='.str_replace('FACTURE-', '',$facture->key[1])); ?></td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
</fieldset>