<?php foreach ($certificationsProduits as $certificationHash => $certificationProduits): ?>
<h2><?php echo $certificationProduits->certification->getLibelle(); ?></h2>
    <table id="table_drm_choix_produit" class="table_recap">
        <thead >
            <tr>                        
                <th>Produit</th>
                <th>Pas de mouvements</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($certificationProduits->produits as $produit): ?>
                <tr>                        
                    <td><?php echo $produit->getLibelle("%format_libelle%"); ?></td>
                    <td><?php echo $form['produit' . $produit->getHashForKey()]->render(); ?></td>
                </tr>  
                <?php ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<div class="choix_produit_add_produit">
<a class="btn_majeur" href="<?php echo url_for('drm_choix_produit_add_produit',array('identifiant' => $drm->identifiant,'periode_version' => $drm->getPeriodeAndVersion(),'certification_hash' => $certificationProduits->certification->getHashForKey()));?>">Ajouter Produits</a> 
</div>
<?php endforeach; ?>