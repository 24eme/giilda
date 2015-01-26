<?php
use_helper('Float');
?>
<h2>10 dernières générations : </h2>
<fieldset>

    <?php
    if (count($generations) == 0):
        ?>
        Aucune génération de relances
        <?php
    else :
        ?>
        <table class="table_recap">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Statut</th>                    
                    <th>Type relance</th>
                    <th>Génération</th>
                    <th>Nb relance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($generations as $generation) :
                    $documents = $generation->value[GenerationClient::HISTORY_VALUES_DOCUMENTS];
                    $types = RelanceClient::getInstance()->getRelancesTypesFromIds($generation->value[GenerationClient::HISTORY_VALUES_DOCUMENTS]);
                    ?>
                    <tr>
                        <td><?php echo GenerationClient::getInstance()->getDateFromIdGeneration($generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION]); ?></td>
                        <td><?php echo $generation->value[GenerationClient::HISTORY_VALUES_STATUT]; ?></td>
                        <td>
                            <?php foreach ($types as $type) : 
                                echo $type."<br/>";
                                endforeach; ?>
                        </td>
                        <td><?php echo link_to($generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION], 'generation_view', array('type_document' => GenerationClient::TYPE_DOCUMENT_RELANCE, 'date_emission' => $generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION])); ?></td>
                        <td><?php
                            echo $generation->value[GenerationClient::HISTORY_VALUES_NBDOC];
                    ?></td>
                    </tr>
    <?php endforeach; ?>
            </tbody>
        </table>
<?php
endif;
?>
</fieldset>
<div class="historique_generation_ds">
    <br/>
    <span>Consulter l'historique des générations de relances</span>
    <a href="<?php echo url_for('generation_list',array('type_document' => GenerationClient::TYPE_DOCUMENT_RELANCE)); ?>" id="historique_generation" class="btn_majeur">Consulter</a>
</div>
