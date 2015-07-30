<?php foreach ($certificationsProduits as $certificationHash => $certificationProduits): ?>
    <?php $certifKey = $certificationProduits->certification->getHashForKey(); ?>
    <h2><?php echo $certificationProduits->certification->getLibelle(); ?> </h2>
    <?php if (count($certificationProduits->produits)): ?>
        <table id = "table_drm_choix_produit" class = "table_recap">
            <thead >
                <tr>
                    <th style="width: 55%;">&nbsp;
                    </th>
                    <th style="width: 45%;">Produit à déclarer ce mois</th>
                </tr>
            </thead>
            <tbody class = "choix_produit_table_<?php echo $certifKey; ?>">
                <?php foreach ($certificationProduits->produits as $produit):
                    ?>
                    <tr>                        
                        <td style="text-align: left;"><?php echo $produit->getLibelle("%format_libelle%"); ?></td>
                        <td class="checkbox_table_cell"><?php echo $form['produit' . $produit->getHashForKey()]->render(array('class' => 'checkbox_' . $certifKey)); ?></td>
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
        <a href="<?php echo url_for('drm_choix_produit', array('sf_subject' => $drm, 'add_produit' => $certificationProduits->certification->getHash())) ?>" value="" class="btn_majeur submit_button">Ajouter des Produits</a>
    </div>
<?php endforeach; ?>