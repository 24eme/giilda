<div id="contenu" class="revendication">
    <!-- #principal -->
    <section id="principal">
        <p id="fil_ariane"><strong>Page d'accueil</strong></p>
        <!-- #contenu_etape -->
        <section id="contenu_etape">
        <h2>Volumes revendiqués</h2>

        <form action="<?php echo url_for('revendication_edition_row', array('odg' => $revendication->odg,
                                'campagne' => $revendication->campagne,
                                'cvi' => $cvi,
                                'row' => $row));?>" method="POST">
        <?php
        echo $form->renderHiddenFields();
        echo $form->renderGlobalErrors();
        ?>
            <fieldset id="revendication_volume_revendiques_edition">
                <table class="table_recap">
                    <thead>
                        <tr>
                            <th>CVI</th>
                            <th>Produit</th>
                            <th>Volume</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $cvi; ?></td>
                            <td><?php echo $form['produit_hash']->render(); ?></td>
                            <td><?php echo $form['volume']->render(); ?></td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="btn_majeur btn_modifier">Modifier</button>
                <div class="f_right">
                <a href="<?php echo url_for('revendication_delete_row',array('odg' => $revendication->odg,
                                                                            'campagne' => $revendication->campagne,
                                                                            'cvi' => $cvi,
                                                                            'row' => $row)); ?>" class="btn_majeur btn_rouge">Supprimer</a>
                </div>
            </fieldset>
            
        </form>
        </section>
    </section>
</div>
