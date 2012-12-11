<?php use_helper('Float') ?>
<?php use_helper('Date') ?>

<div class="section_label_maj" id="calendrier_drm">
   <form method="POST">
   <?php echo $formCampagne->renderGlobalErrors() ?>
   <?php echo $formCampagne->renderHiddenFields() ?>
   <?php echo $formCampagne; ?> <input class="btn_majeur btn_vert" type="submit" value="changer"/>
   </form>
    <table class="table_recap">
        <thead>
            <tr>
                <th>Mois</td>
                <th style="width: 200px;">Produits</td>
                <th>Stock début de mois</th>
                <th>Entrées</th>
                <th>Sorties (Fact.)</th>
                <th><strong>Stock fin de mois</strong></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach($produits->getRawValue() as $produit): ?>
            <?php $i++; ?>
                    <tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
                        <td><?php echo $produit->mois ?></td>
                        <td><?php echo $produit->libelle ?></td>
                        <td><strong><?php echoFloat($produit->total_debut_mois) ?></strong></td>
                        <td><?php echoFloat($produit->total_entrees) ?></td>
                        <td><?php echoFloat($produit->total_sorties) ?> (<?php echoFloat($produit->total_facturable) ?>)</td>
                        <td><strong><?php echoFloat($produit->total) ?></strong></td>
                    </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>