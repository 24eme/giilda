<fieldset id="recapitulatif_sv12">
        <table class="table_recap">
        <thead>
        <tr>
            <th>Appelation</th>
            <th>Volume de raisins</th>
            <th>Volume de moûts</th>
            <th>Total</th>                        
        </tr>
        </thead>
        <tbody>
            <?php foreach ($sv12ByProduitsTypes->rows as $sv12Prod) :  ?>
            <tr>
                <td>
                    <?php echo $sv12Prod->appelation; ?>
                </td>
                <td>
                    <?php echo $sv12Prod->volume_raisins.' hl'; ?>
                </td>

                <td>
                    <?php echo $sv12Prod->volume_mouts.' hl'; ?>
                </td>

                <td>     
                    <?php echo $sv12Prod->volume_total.' hl'; ?>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
            <tr>
                <td style="font-weight:bold;">Total : </td>
                <td style="font-weight:bold;"><?php echo $sv12ByProduitsTypes->volume_raisins.' hl'; ?></td>
                <td style="font-weight:bold;"><?php echo $sv12ByProduitsTypes->volume_mouts.' hl'; ?></td>
                <td style="font-weight:bold;"><?php echo $sv12ByProduitsTypes->volume_total.' hl'; ?></td>
            </tr>
        </tbody>
        </table> 
</fieldset>