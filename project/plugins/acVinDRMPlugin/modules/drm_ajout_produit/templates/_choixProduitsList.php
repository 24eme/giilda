<?php foreach ($certificationsProduits as $certificationHash => $certificationProduits): ?>
    <?php $certifKey = $certificationProduits->certification->getHashForKey(); ?>
    <h2><?php echo $certificationProduits->certification->getLibelle(); ?> </h2>
    <?php if (count($certificationProduits->produits)): ?>
        <table id = "table_drm_choix_produit" class = "table_recap">
            <thead >
                <tr>
                    <th>&nbsp;
                    </th>
                    <th>Produit à déclarer ce mois</th>
                </tr>
            </thead>
            <tbody class = "choix_produit_table_<?php echo $certifKey; ?>">
                <?php foreach ($certificationProduits->produits as $produit):
                    ?>
                    <tr>                        
                        <td><?php echo $produit->getLibelle("%format_libelle%"); ?></td>
                        <td><?php echo $form['produit' . $produit->getHashForKey()]->render(array('class' => 'checkbox_' . $certifKey)); ?></td>
                    </tr>  
                    <?php ?>
                <?php endforeach; ?>
            </tbody>
        </table>    
    <?php else: ?>
     <table id = "table_drm_choix_produit" class = "table_recap">
            <thead >
                <tr>
                    <th>&nbsp;
                    </th>
                    <th>Produit à déclarer ce mois</th>
                </tr>
            </thead>
            <tbody class = "choix_produit_table_<?php echo $certifKey; ?>">                    
                    <tr>                        
                        <td colspan="2">Vous n'avez pas de produit en catégorie <?php echo $certificationProduits->certification->getLibelle(); ?></td>
                      </tr>  
            </tbody>
        </table>    
    <?php endif; ?>
    <div class="choix_produit_add_produit">
        <button type="submit" name="add_produit" value="<?php echo $certificationProduits->certification->getHash() ?>" class="btn_majeur">Ajouter des Produits</button> 
    </div>
<?php endforeach; ?>