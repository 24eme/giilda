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
                    <td><?php echo link_to($facture->value[FactureEtablissementView::VALUE_DATE_EMISSION], array('sf_route' => 'facture_pdf', 'identifiant' => str_replace('ETABLISSEMENT-', '', $facture->key[FactureEtablissementView::KEYS_CLIENT_ID]), 'factureid' => str_replace('FACTURE-' . $etablissement->identifiant . '-', '', $facture->key[FactureEtablissementView::KEYS_FACTURE_ID]))); ?></td>
                    <td><?php foreach ($facture->value[FactureEtablissementView::VALUE_ORIGINES] as $drmid => $drmlibelle) {
                echo link_to($drmlibelle, 'drm_redirect_to_visualisation', array('identifiant_drm' => $drmid)) . "<br/>";
            }; ?></td>
                    <td><?php echoFloat($facture->value[FactureEtablissementView::VALUE_TOTAL_TTC]); ?>&nbsp;€</td>
                    <td><?php
                    echo (FactureClient::getInstance()->isRedressee($facture->value[FactureEtablissementView::VALUE_STATUT]))? 'redressée' :
                        link_to('défacturer les mouvements', '@defacturer?identifiant='.str_replace('FACTURE-', '',$facture->key[FactureEtablissementView::KEYS_FACTURE_ID])); 
                    ?></td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
</fieldset>