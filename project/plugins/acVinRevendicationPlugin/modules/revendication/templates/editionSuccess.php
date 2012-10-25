<?php
use_helper('Float');
?>
<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        <!-- #contenu_etape -->
        <section id="contenu_etape">

            <h2>Volumes revendiqués</h2>
            <fieldset id="revendication_volume_revendiques_edition">
                <table class="table_recap">
                    <thead>
                        <tr>
                            <th>CVI</th>
                            <th>Nom</th>
                            <th>Produit</th>
                            <th style="width: 100px;">Volume (en hl)</th>
                            <th>Editer</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($revendication->datas as $cvi => $etb) :
                        foreach ($etb->produits as $hashKey => $prod) :
                            if($prod->statut != RevendicationProduits::STATUT_SUPPRIME) :
                                
                            foreach ($prod->volumes as $num_row => $volume) :
                                ?>
                                <tr>
                                    <td><?php echo $cvi; ?></td>
                                    <td><?php echo $etb->declarant_nom; ?></td>
                                    <td><?php echo $prod->produit_libelle; ?></td>
                                    <td><?php echoFloat($volume->volume); ?></td>
                                    <td>
                                        <a href="<?php
                                            echo url_for('revendication_edition_row', array('odg' => $revendication->odg,
                                                'campagne' => $revendication->campagne,
                                                'cvi' => $cvi,
                                                'row' => $num_row));
                                        ?>">éditer</a>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                            endif;
                        endforeach;
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </fieldset>
        </section>
    </section>
</div>
