<table id="table_drm_choix_produit" class="table_recap">
        <thead >
            <tr>                        
                <th>Produit</th>
                <th>Pas de mouvements</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($produits as $produit): ?>
           <tr>                        
               <td><?php echo $produit->getLibelle("%format_libelle%"); ?></td>
               <td><?php echo $form['produit'.$produit->getHashForKey()]->render(); ?></td>
            </tr>  
        <?php ?>
    <?php endforeach; ?>
        </tbody>
    </table>