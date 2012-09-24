<?php
use_helper('Date');
?>
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
                    <td><?php $d = format_date($facture->value[FactureEtablissementView::VALUE_DATE_EMISSION],'dd/MM/yyyy');
                        echo link_to($d, array('sf_route' => 'facture_pdf', 'identifiant' => str_replace('ETABLISSEMENT-', '', $facture->key[FactureEtablissementView::KEYS_CLIENT_ID]), 'factureid' => str_replace('FACTURE-' . $etablissement->identifiant . '-', '', $facture->key[FactureEtablissementView::KEYS_FACTURE_ID]))); ?>
                    </td>
                    <td><?php foreach ($facture->value[FactureEtablissementView::VALUE_ORIGINES] as $drmid => $drmlibelle) {

                        $drmIdFormat = (strstr($drmlibelle, 'DRM')!==FALSE)? DRMClient::getInstance()->getLibelleFromIdDRM($drmlibelle) :
                        SV12Client::getInstance()->getLibelleFromIdSV12($drmlibelle);
                echo link_to($drmIdFormat, 'drm_redirect_to_visualisation', array('identifiant_drm' => $drmid)) . "<br/>";
            }; ?></td>
                    <td><?php echoFloat($facture->value[FactureEtablissementView::VALUE_TOTAL_TTC]); ?>&nbsp;€</td>
                    <td><?php
		    $fc = FactureClient::getInstance();
if ($fc->isRedressee($facture)) {
  echo 'redressée';
}else if ($fc->isRedressable($facture)) {
  echo link_to('défacturer les mouvements', '@defacturer?identifiant='.str_replace('FACTURE-', '',$facture->key[FactureEtablissementView::KEYS_FACTURE_ID])); 
}
                    ?></td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
</fieldset>