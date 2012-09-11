<fieldset id="mouvement_sv12">
        <table class="table_recap">
        <thead>
        <tr>
            <th>Date de modification</th>
            <th>Contrat</th>
            <th>Appellation</th>
            <th>Volume</th>

        </tr>
        </thead>
        <tbody>
            <?php foreach ($mouvements as $mouvement) :
            ?>   

            <tr>
                <td></td>
                <td>
                    <?php echo $mouvement->detail_libelle; ?>
                </td>
                <td>
                    <?php echo $mouvement->produit_libelle; ?>
                </td>
                <td>     
                    <?php echo $mouvement->volume.' hl'; ?>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
        </table> 
</fieldset>