   <!-- #principal -->
    <section id="principal" class="generation_facturation">
        <p id="fil_ariane"><strong><?php echo link_to("Page d'accueil", strtolower($type)); ?>  
                <?php
                if (isset($identifiant)) {
                    echo '>' . link_to($nom, strtolower($type) . '_etablissement', array('identifiant' => $identifiant));
                }
                ?>
            </strong> > Visualisation des générations d'impression</p>

        <!-- #contenu_etape -->
        <section id="contenu_etape">
            <h2>Visualisation d'une génération d'impression</h2>
            <table id="ds_recapitulatif_table" class="table_recap">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Quantité</th>
                        <th>Génération</th>
<?php if ($type == GenerationClient::TYPE_DOCUMENT_FACTURES) echo "<th>Montant TTC</th>"; ?>
                    </tr>
                </thead>
                <tbody class="ds_recapitulatif_tableBody">
                    <?php foreach ($historyGeneration as $history) : ?>
                        <tr id="ds_declaration_recapitulatif">
                            <td><?php echo GenerationClient::getInstance()->getDateFromIdGeneration($history->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION]); ?></td>
                            <td><?php echo $history->value[GenerationClient::HISTORY_VALUES_STATUT]; ?></td>                            
                            <td><?php echo $history->value[GenerationClient::HISTORY_VALUES_NBDOC]; ?></td>
                            
                            <td><?php echo link_to($history->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION], 'generation_view', array('type_document' => $type, 'date_emission' => $history->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION])); ?></td>
<?php if ($type == GenerationClient::TYPE_DOCUMENT_FACTURES) {echo "<td>";printf("%.02f €", $history->value[GenerationClient::HISTORY_VALUES_SOMME]); echo"</td>";} ?>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table> 
        </section>
     </section>
        <!-- fin #contenu_etape -->