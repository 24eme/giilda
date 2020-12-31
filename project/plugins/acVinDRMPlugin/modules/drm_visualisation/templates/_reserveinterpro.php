<?php
$produit_ri = $drm->getProduitsReserveInterpro();
if (count($produit_ri)): ?>
    <div id="contenu_onglet">
        <h2>Reserve interprofessionnelle</h2>
        <table class="table_recap">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Réserve interprofessionnelle</th>
                    <th>Stock commercialisable</th>
                </tr>
            </thead>
            <tbody>
<?php foreach($produit_ri as $p): ?>
                <tr>
                    <td><?php echo $p->getLibelle() ?></td>
                    <td class="text-right"><?php echoFloat($p->getRerserveIntepro()); ?>&nbsp;hl</td>
                    <td class="text-right"><?php echoFloat($p->getVolumeCommercialisable()); ?>&nbsp;hl</td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
