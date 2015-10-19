<?php foreach ($certificationsProduits as $certificationHash => $certificationProduits): ?>
    <?php $certifKey = $certificationProduits->certification_libelle; ?>
        <div class="col-xs-12">
            <h3><?php echo $certificationProduits->certification_libelle; ?></h3>
            <table id="table_drm_choix_produit" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-7 text-left">Produits <a data-form="#form_choix_produits" href="<?php echo url_for('drm_choix_produit', array('sf_subject' => $drm, 'add_produit' => $certificationProduits->certification_keys)) ?>" value="" class="btn btn-link btn-xs link-submit pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter un produit</a>
                        </th>
                        <th class="col-xs-5 text-center">Mouvements à déclarer ce mois</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($certificationProduits->produits)): ?>
                        <?php foreach ($certificationProduits->produits as $produit):
                            ?>
                            <tr>                        
                                <td class="text-left"><?php echo $produit->getLibelle("%format_libelle%"); ?></td>
                                <td class="text-center"><?php echo $form['produit' . $produit->getHashForKey()]->render(); ?></td>
                            </tr>  
                            <?php ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>                        
                            <td class="text-center" colspan="2"><em>Vous n'avez pas de produit déclaré en catégorie <?php echo $certificationProduits->certification_libelle; ?></em></td>
                        </tr>  
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
<?php endforeach; ?>