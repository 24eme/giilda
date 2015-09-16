<div class="row">
<?php foreach ($certificationsProduits as $certificationHash => $certificationProduits): ?>
    <?php $certifKey = $certificationProduits->certification_libelle; ?>
    <div class="col-xs-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?php echo url_for('drm_choix_produit', array('sf_subject' => $drm, 'add_produit' => $certificationProduits->certification_keys)) ?>" value="" class="btn btn-link btn-xs submit_button pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter un produit</a>
            <h3 class="panel-title text-center"><?php echo $certificationProduits->certification_libelle; ?></h3>
        </div>
    <?php if (count($certificationProduits->produits)): ?>
        <table id="table_drm_choix_produit" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="col-xs-7">&nbsp;
                    </th>
                    <th class="col-xs-5">Produit à déclarer ce mois</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($certificationProduits->produits as $produit):
                    ?>
                    <tr>                        
                        <td style="text-align: left;"><?php echo $produit->getLibelle("%format_libelle%"); ?></td>
                        <td class="checkbox_table_cell"><?php echo $form['produit' . $produit->getHashForKey()]->render(); ?></td>
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
                        <td colspan="2">Vous n'avez pas de produit en catégorie <?php echo $certificationProduits->certification_libelle; ?></td>
                      </tr>  
            </tbody>
        </table>    
    <?php endif; ?>
    </div>
    </div>
<?php endforeach; ?>
</div>