<?php
$produit_ri = $drm->getProduitsReserveInterpro();
if (count($produit_ri)): ?>
    <div id="contenu_onglet">
        <h2>Reserve interprofessionelle</h2>
        <table class="table_recap">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Réserver interprofessionelle</th>
                    <th>Stock commercialisable</th>
                </tr>
            </thead>
            <tbody>
<?php foreach($produit_ri as $p): ?>
                <tr>
                    <td><?php echo $p->getLibelle() ?></td>
                    <td><?php echoFloat($p->getRerserveIntepro()); ?></td>
                    <td><?php echoFloat($p->getVolumeCommercialisable()); ?></td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
