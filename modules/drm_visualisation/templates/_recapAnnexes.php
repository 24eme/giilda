<?php if (count($drm->documents_annexes) || ($drm->exist('releve_non_apurement') && count($drm->releve_non_apurement)) || $drm->quantite_sucre || $drm->observations): ?>
    <div class="row">
        <div class="col-xs-12">

            <h3>Annexes</h3>
            <ul class="list-group">
                <?php foreach ($drm->documents_annexes as $typeDoc => $numsDoc): ?>
                    <li class="list-group-item"><strong>Documents d'accompagnements :</strong> <?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?> du n°<?php echo $numsDoc->debut; ?> au <?php echo $numsDoc->fin; ?></li>
                <?php endforeach; ?> 
                <?php
                if ($drm->exist('releve_non_apurement')):
                    foreach ($drm->releve_non_apurement as $num_non_apurement => $non_apurement):
                        ?>
                        <li class="list-group-item"><strong>Relevé de non apurement :</strong> n°<?php echo $non_apurement->numero_document; ?> expédié le <?php echo $non_apurement->date_emission; ?> pour le n° d'accise <?php echo $non_apurement->numero_accise; ?></li>
                    <?php
                    endforeach;
                endif;
                ?>
                <?php
                foreach ($drm->getObservationsArray() as $produitLibelle => $observation): ?>
                    <li class="list-group-item"><strong>Observation <?php echo $produitLibelle; ?> :</strong> <?php echo $observation; ?> </li>
                <?php
                endforeach;
                ?>
                <?php if ($drm->quantite_sucre): ?>
                    <li class="list-group-item"><strong>Quantité de sucres :</strong> <?php echo $drm->quantite_sucre ?> quintals</li>
                    <?php endif; ?>
                    <?php if ($drm->observations): ?>
                    <li class="list-group-item"><strong>Observations sur les mouvements :</strong> <?php echo $drm->observations; ?></li>
    <?php endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
