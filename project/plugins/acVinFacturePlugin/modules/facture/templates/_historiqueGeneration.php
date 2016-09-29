<?php use_helper('Float'); ?>
<div class="row">
    <div class="col-xs-12">
        <h3>10 dernières générations <small>(<a href="<?php echo url_for('generation_list', array('type_document' => GenerationClient::TYPE_DOCUMENT_FACTURES)); ?>">voir tout</a>)</small></h2>

        <?php if (count($generations) == 0): ?>
            Aucune génération de facture
        <?php else: ?>
            <div class="list-group">
                <?php
                foreach ($generations as $generation) :
                    $documents = $generation->value[GenerationClient::HISTORY_VALUES_DOCUMENTS];
                    ?>
                    <li class="list-group-item col-xs-12">
                        <span class="col-xs-2"><?php echo GenerationClient::getInstance()->getDateFromIdGeneration($generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION]); ?></span>
                        <span class="col-xs-2"><?php echo $generation->value[GenerationClient::HISTORY_VALUES_STATUT]; ?></span>
                        <span class="col-xs-3">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php echo $generation->value[GenerationClient::HISTORY_VALUES_NBDOC] . ' '.$generation->key[GenerationClient::HISTORY_KEYS_TYPE_DOCUMENT].'(s)'; ?>
                                </div>
                                <!--<div class="col-xs-12">
                                    <?php foreach ($generation->value[GenerationClient::HISTORY_VALUES_DOCUMENTS] as $cpt => $facture) : ?>
                                        <?php echo $facture; ?>
                                    <?php echo ($cpt+1 < $generation->value[GenerationClient::HISTORY_VALUES_NBDOC])? ', ' : ''; ?>     
                                    <?php endforeach; ?>
                                </div>-->
                            </div>
                        </span>
                        <span class="col-xs-2 text-right">
                        <?php echo link_to($generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION], 'generation_view', array('type_document' => $generation->key[GenerationClient::HISTORY_KEYS_TYPE_DOCUMENT], 'date_emission' => $generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION])); ?>
                        </span>
                        <span class="col-xs-2 text-right"><?php if ($generation->value[GenerationClient::HISTORY_VALUES_SOMME]): 
                            echoFloat($generation->value[GenerationClient::HISTORY_VALUES_SOMME]);
                            ?>&nbsp;€ HT<?php endif; ?></span>
                        <span class="col-xs-1 text-right">
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
