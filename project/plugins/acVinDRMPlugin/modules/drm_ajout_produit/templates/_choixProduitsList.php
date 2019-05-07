<?php foreach ($certificationsProduits as $certificationHash => $certificationProduits): ?>
    <?php $certifKey = $certificationProduits->certification_libelle; ?>
    <h2><?php echo $certificationProduits->certification_libelle; ?> &nbsp;<a href="" class="msg_aide_drm icon-msgaide" title="<?php echo getHelpMsgText('drm_produits_aide1'); ?>"></a></h2>
    <?php if (count($certificationProduits->produits)): ?>
        <table id = "table_drm_choix_produit" class = "table_recap">
            <thead >
                <tr>
                    <th style="width: 50%;">&nbsp;
                    </th>
                    <th style="width: 25%;">Produit à déclarer ce mois en droits suspendus&nbsp;<a href="" class="msg_aide_drm icon-msgaide" title="<?php echo getHelpMsgText('drm_produits_aide2'); ?>" style="float:right; padding: 0 10px 0 0;"></a></th>
                    <?php if ($drm->getConfig()->declaration->hasAcquitte() && $drm->hasEtablissementDroitsAcquittes()): ?>
	                     <th style="width: 25%;">Produit à déclarer ce mois en droits acquittés</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($certificationProduits->produits as $produit): ?>
                    <tr>
                        <td style="text-align: left;"><?php echo $produit->getLibelle("%format_libelle%"); ?></td>
                        <td class="checkbox_table_cell"><?php echo $form['produit' . $produit->getHashForKey()]->render(); ?></td>
                          <?php if ($drm->getConfig()->declaration->hasAcquitte() && $drm->hasEtablissementDroitsAcquittes()): ?>
                    	       <td class="checkbox_table_cell"><?php echo $form['acquitte' . $produit->getHashForKey()]->render(); ?></td>
                         <?php endif; ?>
                    </tr>
                    <?php ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
     <table id = "table_drm_choix_produit" class = "table_recap">
            <thead >
                <tr>
                    <th style="width: 50%;">&nbsp;
                    </th>
                    <th>Produit à déclarer ce mois en droits suspendus&nbsp;<a href="" class="msg_aide_drm icon-msgaide" title="<?php echo getHelpMsgText('drm_produits_aide2'); ?>" style="float:right; padding: 0 10px 0 0;"></a></th>
                    <?php if ($drm->getConfig()->declaration->hasAcquitte() && $drm->hasEtablissementDroitsAcquittes()): ?>
		                    <th style="width: 25%;">Produit à déclarer ce mois en droits acquittés</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class = "choix_produit_table_<?php echo $certifKey; ?>">
                    <tr>
                        <td colspan="3">Vous n'avez pas de produit en catégorie <?php echo $certificationProduits->certification_libelle; ?></td>
                      </tr>
            </tbody>
        </table>
    <?php endif; ?>
    <div class="choix_produit_add_produit clearfix" style="padding: 5px">
        <a href="<?php echo url_for('drm_choix_produit', array('sf_subject' => $drm, 'add_produit' => $certificationProduits->certification_keys)) ?>" class="btn_majeur submit_button">Ajouter des Produits</a>
    </div>
<?php endforeach; ?>
