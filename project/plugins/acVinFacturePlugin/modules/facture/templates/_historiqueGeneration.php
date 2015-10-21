<?php use_helper('Float'); ?>
<div class="row">
    <div class="col-xs-12">
        <h2>10 dernières générations : </h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php if (count($generations) == 0): ?>
            Aucune génération de facture
        <?php else: ?>
            <div class="list-group">
                <?php
                foreach ($generations as $generation) :
                    $documents = $generation->value[GenerationClient::HISTORY_VALUES_DOCUMENTS];
                    ?>
                    <li class="list-group-item col-xs-12">
                        <span class="col-xs-1"><?php echo GenerationClient::getInstance()->getDateFromIdGeneration($generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION]); ?></span>
                        <span class="col-xs-2"><?php echo $generation->value[GenerationClient::HISTORY_VALUES_STATUT]; ?></span>
                        <span class="col-xs-4"><?php echo $date; ?></span>
                        <?php echo link_to($generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION], 'generation_view', array('type_document' => GenerationClient::TYPE_DOCUMENT_FACTURES, 'date_emission' => $generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION])); ?>
                        <span class="col-xs-2 text-right"><?php
                            echoFloat($generation->value[GenerationClient::HISTORY_VALUES_SOMME]);
                            ?>&nbsp;€ TTC</span>
                        <span class="col-xs-3 text-right">
                            <?php
                            echo $generation->value[GenerationClient::HISTORY_VALUES_NBDOC];
                            ?></span>

                    </li>
                <?php endforeach; ?>
            </div>
        <?php
        endif;
        ?>
    </div>
</div>
<div class="historique_generation_ds" style="padding:10px;">
    <span>Consulter l'historique des générations de factures</span>
    <a href="<?php echo url_for('generation_list', array('type_document' => GenerationClient::TYPE_DOCUMENT_FACTURES)); ?>" id="historique_generation" class="btn_majeur">Consulter</a>
</div>
