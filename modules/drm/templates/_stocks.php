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
                <th>Sorties</th>
                <th><strong>Stock fin de mois</strong></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach($details->getRawValue() as $detail): ?>
            <?php $i++; ?>
                    <tr <?php if($i%2!=0) echo ' class="alt"'; ?>>
                        <td><?php echo $detail->mois ?></td>
                        <td><?php echo $detail->libelle ?></td>
                        <td><strong><?php echoFloat($detail->total_debut_mois) ?></strong>&nbsp;<span class="unite">hl</span></td>
                        <td><?php echoFloat($detail->total_entrees) ?>&nbsp;<span class="unite">hl</span></td>
                        <td><?php echoFloat($detail->total_sorties) ?>&nbsp;<span class="unite">hl</span></td>
                        <td><strong><?php echoFloat($detail->total) ?></strong>&nbsp;<span class="unite">hl</span></td>
                    </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>