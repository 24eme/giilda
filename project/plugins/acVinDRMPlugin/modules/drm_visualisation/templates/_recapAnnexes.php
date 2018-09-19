<?php if (count($drm->documents_annexes) + count($drm->documents_annexes)): ?>
    <h2>Documents d'accompagnement</h2>
<?php endif; ?>
<?php if (count($drm->documents_annexes)): ?>
    <table id="table_drm_adminitration" class="table_recap">
        <thead >
            <tr>
                <th>Type de document</th>
                <th>Numéro de début</th>
                <th>Numéro de fin</th>
                <th>Nombre de document(s)</th>
            </tr>
        </thead>
        <tbody class="drm_adminitration">
            <?php foreach ($drm->documents_annexes as $typeDoc => $numsDoc): ?>
                <tr>
                    <td class="drm_annexes_type"><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>
                    <td class="drm_annexes_doc_debut"><?php echo $numsDoc->debut; ?></td>
                    <td class="drm_annexes_doc_fin"><?php echo $numsDoc->fin; ?></td>
                    <td class="drm_annexes_doc_fin"><?php echo $numsDoc->nb; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br/>
    <br>
<?php endif; ?>
<?php if ($drm->exist('releve_non_apurement') && count($drm->releve_non_apurement)): ?>
    <h2>Relevé de non apurement</h2>
    <table id="table_drm_non_apurement" class="table_recap">
        <thead >
            <tr>
                <th>Numéro de document</th>
                <th class="drm_non_apurement_date_emission">Date d'expédition</th>
                <th>Numéro d'accises</th>
            </tr>
        </thead>
        <tbody class="drm_non_apurement" id="nonapurement_list">
            <?php foreach ($drm->releve_non_apurement as $num_non_apurement => $non_apurement): ?>
                <tr>
                    <td class="drm_non_apurement_numero_document"><?php echo $non_apurement->numero_document; ?></td>
                    <td class="drm_non_apurement_date_emission"><?php echo $non_apurement->date_emission; ?></td>
                    <td class="drm_non_apurement_numero_accise"><?php echo $non_apurement->numero_accise; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if ($drm->quantite_sucre || $drm->observations): ?>
    <h2>Compléments d'information</h2>
<?php endif; ?>
<?php if ($drm->quantite_sucre): ?>
    <table id="table_drm_complement_informations" class="table_recap">
        <thead >
            <tr>
                <th colspan="2">Information sur le sucre</th>
            </tr>
        </thead>
        <tbody class="drm_non_apurement" id="nonapurement_list">
            <tr>
                <td class="drm_quantite_sucre_label">Quantité de sucre</td>
                <td class="drm_quantite_sucre_volume">
                    <?php echo $drm->quantite_sucre ?> q.
                </td>
            </tr>
        </tbody>
    </table>
    <?php if ($drm->observations): ?>
        <br/>
    <?php endif; ?>
<?php endif; ?>
<?php if ($drm->observations): ?>
    <table id="table_drm_complement_informations_observation" class="table_recap">
        <thead >
            <tr>
                <th>Observations générales</th>
            </tr>
        </thead>
        <tbody class="drm_non_apurement" id="nonapurement_list">
            <tr>
                <td class="drm_observation">
                    <?php echo $drm->observations; ?>
                </td>
            </tr>
        </tbody>

    </table>
<?php endif; ?>
<?php if($drm->hasTavs()): ?>
    <br/>
    <h2>TAV enregistrés</h2>
    <table id="table_drm_tavs" class="table_recap">
        <thead >
            <tr>
                <th>Produits</th><th>Tav</th>
            </tr>
        </thead>
        <tbody class="drm_tavs" id="tavs_list">
            <?php
            foreach ($drm->getTavsArray() as $produitLibelle => $tav):
                ?>
            <tr>
                <td class="drm_produit_tavs">
                    <?php echo $produitLibelle; ?>
                </td>
                <td class="drm_volume_tavs">
                    <?php echoFloat($tav); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>
<?php endif; ?>
