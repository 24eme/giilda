<?php foreach ($certificationsProduits as $certificationHash => $certificationProduits): ?>
<?php $certifKey = $certificationProduits->certification->getHashForKey(); ?>
<h2><?php echo $certificationProduits->certification->getLibelle(); ?></h2>
    <table id="table_drm_choix_produit" class="table_recap">
        <thead >
            <tr>                        
                <th>&nbsp;</th>
                <th>Produit à déclarer ce mois</th>
            </tr>
        </thead>
        <tbody class="choix_produit_table_<?php echo $certifKey; ?>">
             <tr>                        
                    <td>&nbsp;</td>
                    <td><span>Tout sélectionner</span><input type="checkbox" <?php echo ($form->isAllChecked())? "checked='checked'" : ""; ?> class="checkbox_all_<?php echo $certifKey; ?>" /></td>
                </tr>
            <?php foreach ($certificationProduits->produits as $produit): ?>
                <?php echo $produit->getCepage()->getConfig()->formatProduitLibelle(); ?>
                <tr>                        
                    <td><?php echo $produit->getCepage()->getConfig()->formatProduitLibelle(); ?></td>
                    <td><?php echo $form['produit' . $produit->getHashForKey()]->render(array('class' => 'checkbox_'.$certifKey)); ?></td>
                </tr>  
                <?php ?>
            <?php endforeach; ?>
        </tbody>
    </table>

<div class="choix_produit_add_produit">
<a class="btn_majeur ajout_produit_popup" href="#add_produit_<?php echo $certifKey; ?>">Ajouter Produits</a> 
</div>
<?php endforeach; ?>