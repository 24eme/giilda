<h2>Import des volumes revendiqués</h2>
<fieldset id="revendication_syntaxeErrors">
    <a href="<?php echo url_for('revendication/downloadCSV?md5=' . $md5); ?>" class="btn_majeur btn_orange">Download CSV</a>
    <p>
        <table class="table_recap">
            <thead>
                <tr>
                    <th>Numéro de ligne</th>
                    <th>Problème détecté</th>
                </tr>
            </thead>
        <?php
            foreach ($errors as $error) {
                echo "<tr><td>" . $error['num_ligne'] . "</td><td>" . $error['message'] . "</td></tr>";
            }
         ?>
         </table>
    </p>
</fieldset>
