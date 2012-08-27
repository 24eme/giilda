<fieldset id="mouvement_sv12">
        <table class="table_recap">
        <thead>
        <tr>
            <th style="width: 200px;">Viticulteur </th>
            <th>Appelation</th>
            <th>Contrat</th>
            <th>Volume</th>

        </tr>
        </thead>
        <tbody>
            <?php foreach ($sv12->contrats as $contrat) :
            ?>   

            <tr>
                <td>
                    <?php echo $contrat->vendeur_nom.' ('.$contrat->vendeur_identifiant.')'; ?>
                </td>
                <td>
                    <?php echo $contrat->produit_libelle; ?>
                </td>

                <td>
                    <?php echo $contrat->contrat_numero; ?>
                </td>

                <td>     
                    <?php echo $contrat->volume; ?>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
        </table> 
</fieldset>