<table class="table_recap table_annuaire">
    <thead>
        <tr>
            <th colspan="2"><?=$personnalite?><?= ($personnalite == 'Commerciaux') ?'':'s'?> (<?= count($annuaire->$type) ?>)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($annuaire->$type) > 0): ?>
            <?php foreach ($annuaire->$type as $key => $item): ?>
                <tr<?= ($item->isActif) ? '' : " class='suspendu'"?>>
                    <td>
                        <?= $item->name ?>
                        <?php if (! $item->isActif) : ?>
                            <span class="red">SUSPENDU</span>
                        <?php endif;?>
                        <br/>
                        <span><?= $key; ?> &middot; CVI: <?= $item->cvi ?> &middot; N° Accises: <?= $item->accises ?></span>
                    </td>
                    <td><a href="<?= url_for('annuaire_supprimer', array('type' => $type, 'id' => $key, 'identifiant' => $etablissement->identifiant)) ?>" onclick='return confirm("Confirmez-vous la suppression du <?= $personnalite ?> ?")' class="btn_supprimer">X</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td><span>Aucun <?= $personnalite ?></span></td></tr>
        <?php endif; ?>
    </tbody>
</table>
