<h2>Import des volumes revendiqués</h2>
<fieldset id="revendication_syntaxeErrors">
    <a href="<?php echo url_for('revendication_downloadCSV', array('md5' => $md5, 'odg' => $odg, 'campagne' => $campagne)); ?>" class="btn_majeur btn_orange">Download CSV</a>
        <table style="margin-top: 20px;" class="table_recap">
            <thead>
                <tr>
                    <th>Numéro de ligne</th>
                    <th>Problème détecté</th>
                </tr>
            </thead>
            <tbody>
                
        <?php
            foreach ($errors as $error) {
                echo "<tr><td>" . $error['num_ligne'] . "</td><td>" . $error['message'] . "</td></tr>";
            }
         ?>
            </tbody>
         </table>
</fieldset>
