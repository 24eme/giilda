<?php
use_helper('Float');
?>
<!-- #principal -->
<section id="principal" class="revendication">
    <p id="fil_ariane"><strong>Page d'accueil</strong></p>
    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <h2>Volumes revendiqués</h2>

        <form id="volumes_revendiques" action="<?php
echo url_for('revendication_add_row', array('odg' => $odg,
    'campagne' => $campagne));
?>" method="post">
                  <?php
                  echo $form->renderHiddenFields();
                  echo $form->renderGlobalErrors();
                  ?>
            <div class="section_label_maj" id="recherche_operateur">
                <?php echo $form['etablissement']->renderError(); ?>
                <?php echo $form['etablissement']->renderLabel(); ?>
                <?php echo $form['etablissement']->render(); ?>
            </div>
            <table class="table_recap">
                <thead>
                    <tr>
                        <th>ODG</th>
                        <th>Campagne</th>
                        <th>Produit</th>
                        <th >Volume (en hl)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $revendication->odg; ?></td>
                        <td><?php echo $revendication->campagne; ?></td>
                        <td><?php echo $form['produit_hash']->render();
                echo $form['produit_hash']->renderError(); ?></td>
                        <td class="volume"><?php echo $form['volume']->render();
                echo $form['volume']->renderError();
                ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="form_btn">
                <a href="<?php echo url_for('revendication_edition', array('odg' => $odg, 'campagne' => $campagne)); ?>" class="btn_majeur btn_modifier">Annuler</a>&nbsp;

                <button type="submit" class="btn_majeur btn_valider">Ajouter la ligne</button>
            </div>
        </form>
    </section>
</section>
<?php
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('revendication'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('revendication_edition', array('odg' => $revendication->odg, 'campagne' => $revendication->campagne)); ?>" class="btn_majeur btn_acces"><span>Retour à l'édition</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>

