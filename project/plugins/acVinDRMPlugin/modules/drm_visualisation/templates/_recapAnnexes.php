<?php if (count($drm->documents_annexes) + count($drm->documents_annexes)): ?>
    <h2>Documents d'accompagnement</h2>
<?php endif; ?> 
<?php if (count($drm->documents_annexes)): ?>
    <?php foreach ($drm->documents_annexes as $typeDoc => $numsDoc): ?>
        <table id="table_drm_adminitration" class="table_recap">
            <thead >
                <tr>   
                    <th class="drm_annexes_type"></th>
                    <th colspan="2">Document d'accompagnement <?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></th>
                </tr>
            </thead>
            <tbody class="drm_adminitration">
                <tr> 
                    <td class="drm_annexes_type"><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>                       
                    <td class="drm_annexes_doc_debut"><?php echo $numsDoc->debut; ?></td>
                    <td class="drm_annexes_doc_fin"><?php echo $numsDoc->fin; ?></td>
                </tr>
            </tbody>
        </table>
        <br/>
    <?php endforeach; ?>  
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