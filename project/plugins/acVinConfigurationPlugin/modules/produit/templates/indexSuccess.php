<?php slot('global_css_class', 'no_right_col')?>

<section id="principal"  class="produit" style="padding-right: 5px; padding-left: 5px;">
    <p id="fil_ariane"><strong>Page d'accueil</strong></p>
    <a style="float:right" href="<?php echo url_for('produit_nouveau') ?>" class="btn_majeur btn_nouveau">Ajouter un produit</a>

    <h2>Produits <span style="color: #878787;"> / <?php echo $config->_id ?><small style="font-size: 10px;">@<?php echo $config->_rev ?></small></span></h2>

    <table class="table_recap table_compact">
        <thead>
            <?php include_partial('produit/itemHeader') ?>
        </thead>
        <tbody>
        <?php foreach($produits as $produit): ?>
            <?php include_component('produit', 'item', array('produit' => $produit, 'date' => $date, 'supprimable' => false)) ?>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a name="mouvements"></a>
    <h2>Configuration des mouvements</h2>

    <table class="table_recap table_compact">
        <thead>

        </thead>
        <tbody>
          <?php foreach ($config->declaration->filter('details') as $configDetails): ?>
              <?php foreach ($configDetails as $details): ?>
                <?php foreach($config->declaration->details->getDetailsSorted($details) as $detail): ?>
                  <tr>
                    <td style="text-align:left;"><span style="<?php if($detail->mouvement_coefficient == -1): ?>color: #ff0000;<?php endif; ?><?php if($detail->mouvement_coefficient == 1): ?>color: #0aaa25;<?php endif; ?>"><?php echo $detail->getParent()->getKey() ?></span></td>
                    <td style="text-align:left;"><span title="<?php echo $detail->libelle_long ?>"><?php echo $detail->getLibelle() ?></span> <small style="color: #555; font-size: 11px;">(<?php echo $detail->getKey() ?>)</small></td>
                    <td><small style="color: #555; font-size: 11px;"><?php echo $detail->douane_cat; ?></small></td>
                    <td><?php if($detail->isFavoris()): ?><img src="/images/pictos/pi_fullstar.png" /><?php endif; ?></td>
                    <td><?php if($detail->facturable): ?>CVO<?php endif; ?></td>
                    <td><?php if($detail->taxable_douane): ?>DOUANE<?php endif; ?></td>
                    <td><?php if($detail->details): ?>DETAILS<?php endif; ?></td>
                    <td><?php if($detail->vrac): ?>VRAC<?php endif; ?></td>
                    <td><?php if($detail->readable): ?>R<?php endif; ?><?php if($detail->writable): ?>W<?php endif; ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

</section>
