<!-- #principal -->
<section id="principal">
    <p id="fil_ariane"><strong>Rapport d'import</p>

<?php
if (count($rapport) > 0) :
    ?>
    <div class="section_label_maj" id="societe_creation_history">
        <h2> Rapport d'import</h2>
        <table class="table_recap">
            <thead>
                <tr>
                    <th>Ligne</th>
                    <th>Message</th>
            </thead>
            <tbody>
                <?php foreach ($rapport as $num_line => $rapport_line) :
                    $background_erreur_style = ($rapport_line["type"] == "ERREUR")? 'style="background-color:darkred; color:#fff;"' : '';
                    ?>
                    <tr>
                        <td <?php echo $background_erreur_style; ?> >
                            <?php echo sprintf("%04d",str_replace('ligne_', '', $num_line)); ?>
                        </td>
                        <td>
                            <?php echo $rapport_line['msg']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
        </table>
    </div> 
<?php endif; ?> 
</section>

<?php
slot('colButtons');
?>
<div id="action" class="bloc_col" >
    <h2>Actions</h2>

    <div class="contenu">
        <ul>
            <li class=""><a class="btn_majeur btn_acces" href="<?php echo url_for('societe'); ?>">Revenir aux sociétés</a></li>
            <li class=""><a class="btn_majeur btn_acces" href="<?php echo url_for('societe_creation'); ?>">Créer une société</a></li>
        </ul>  
    </div>
</div>
<?php
end_slot();
slot('colApplications');
?>

<div id="import_rgt_en_attente" class="bloc_col">
    <h2>Ajouter les rgt en attente</h2>
    <?php
    include_partial('uploadCsvForRgtEnAttente', array('formUploadCSVNoCVO' => $formUploadCSVNoCVO));
    ?>
</div> 
<?php
end_slot();
?>



