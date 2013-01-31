<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><a href="<?php echo url_for('societe');?>">Page d'accueil</a>
        &gt; <a href="<?php echo url_for('societe_creation');?>">Création d'une société </a>
        &gt; <strong>Sociétés existantes</strong></p>

    <!-- #contenu_etape -->
    <section id="contenu_etape">
        <h2>Sociétés existantes avec la raison sociale "<?php echo $raison_sociale; ?>" (<?php echo $type;?>)</h2>
<table class="table_recap">
    <thead>
        <tr>
            <th>Raison sociale</th>
            <th>Type de société</th>
            <th>Identifiant</th>
            <th>Statut</th>
            <th>Code postal</th>
            <th>Commune</th>
    </thead>
    <tbody>
        <?php foreach ($societesDoublons as $societeDoublee) : ?>
            <tr>
                <td>
                    <a href="<?php echo url_for('societe_visualisation', array('identifiant' => $societeDoublee->key[SocieteAllView::KEY_IDENTIFIANT])); ?>">
                        <?php echo $societeDoublee->key[SocieteAllView::KEY_RAISON_SOCIALE]; ?>
                    </a>
                </td>
                <td>
                    <?php echo $societeDoublee->key[SocieteAllView::KEY_TYPESOCIETE]; ?>
                </td>
                <td>
                    <?php echo $societeDoublee->key[SocieteAllView::KEY_IDENTIFIANT]; ?>
                </td>
                <td>
                    <?php echo $societeDoublee->key[SocieteAllView::KEY_STATUT]; ?>
                </td>
                <td>
                    <?php echo $societeDoublee->key[SocieteAllView::KEY_CODE_POSTAL]; ?>
                </td>
                <td>
                    <?php echo $societeDoublee->key[SocieteAllView::KEY_COMMUNE]; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
    <div class="form_btn">
        <a href="<?php echo url_for('societe_creation'); ?>" class="btn_majeur btn_annuler">Annuler</a>                   
        <a id="btn_valider" href="<?php echo url_for('societe_nouvelle',array('type' => $type,'raison_sociale' => $raison_sociale)); ?>" class="btn_majeur btn_valider">Créer</a>
    </div>
</section>
<?php  
slot('colButtons');
?>
<div id="action" class="bloc_col">
    <h2>Action</h2>
    <div class="contenu">
        <div class="btnRetourAccueil">
            <a href="<?php echo url_for('societe'); ?>" class="btn_majeur btn_acces"><span>Retour à l'accueil</span></a>
        </div>
    </div>
</div>
<?php
end_slot();
?>