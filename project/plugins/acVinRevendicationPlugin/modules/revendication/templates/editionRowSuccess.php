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

            <form id="volumes_revendiques" action="<?php
echo url_for('revendication_edition_row', array('odg' => $revendication->odg,
    'campagne' => $revendication->campagne,
    'identifiant' => $identifiant,
    'row' => $row,
    'retour' => $retour));
?>" method="post">
                      <?php
                      echo $form->renderHiddenFields();
                      echo $form->renderGlobalErrors();
                      ?>
                <table class="table_recap">
                    <thead>
                        <tr>
                            <th>ODG</th>
                            <th>CVI</th>
                            <th>Nom</th>
                            <th>Produit</th>
                            <th >Volume (en hl)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $revendication->odg; ?></td>
                            <td><?php echo $rev->declarant_cvi; ?></td>
                            <td><?php echo $rev->declarant_nom; ?></td>
                            <td><?php echo $form['produit_hash']->render(); ?></td>
                            <td class="volume"><?php echo $form['volume']->render(); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="form_btn">
                    <a href="<?php
                      echo url_for('revendication_delete_row', array('odg' => $revendication->odg,
                          'campagne' => $revendication->campagne,
                          'identifiant' => $identifiant,
                          'row' => $row));
                      ?>" class="btn_majeur btn_annuler">Supprimer</a>
                    
                    <a href="<?php
                      echo url_for('revendication_edition', array('odg' => $revendication->odg,
                          'campagne' => $revendication->campagne));
                      ?>" class="btn_majeur btn_modifier">Annuler</a>&nbsp;


                      <button type="submit" class="btn_majeur btn_valider">Modifier</button>
                </div>
            </form>
        </section>
    </section>
</div>
