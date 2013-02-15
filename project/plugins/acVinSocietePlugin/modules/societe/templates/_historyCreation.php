<?php
if(count($societes_creation) > 0) :
?>
<div class="section_label_maj" id="societe_creation_history">
    <h2> Sociétés en cours de création</h2>
    <table class="table_recap">
    <thead>
        <tr>
            <th>Raison sociale</th>
            <th>Type de société</th>
            <th>Identifiant</th>
            <th>Saisir</th>
            <th>Supprimer</th>
    </thead>
    <tbody>
        <?php foreach ($societes_creation as $soc) : ?>
            <tr>
                <td>
                    <?php echo $soc->key[SocieteAllView::KEY_RAISON_SOCIALE]; ?>
                </td>
                <td>
                    <?php echo $soc->key[SocieteAllView::KEY_TYPESOCIETE]; ?>
                </td>
                <td>
                    <?php echo $soc->key[SocieteAllView::KEY_IDENTIFIANT]; ?>
                </td>
                <td>
                    <a href="<?php echo url_for('societe_modification', array('identifiant' => $soc->key[SocieteAllView::KEY_IDENTIFIANT])); ?>">
                        Finir la saisie
                    </a>
                </td>
                <td>
                    <a href="<?php echo url_for('societe_annulation', array('identifiant' => $soc->key[SocieteAllView::KEY_IDENTIFIANT], 'back_home' => true)); ?>">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div> 
<?php endif; ?> 