<div class="row">

<?php if (count($drm->documents_annexes)): ?>
<div class="col-xs-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-center">Documents d'accompagnement</h3>
        </div>
        <table class="table table-bordered table-striped">
            <thead >
                <tr>   
                    <th>Type de document</th>
                    <th>Numéro de début</th>
                    <th>Numéro de fin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($drm->documents_annexes as $typeDoc => $numsDoc): ?>
                    <tr> 
                        <td><?php echo DRMClient::$drm_documents_daccompagnement[$typeDoc]; ?></td>                       
                        <td><?php echo $numsDoc->debut; ?></td>
                        <td><?php echo $numsDoc->fin; ?></td>
                    </tr>
                <?php endforeach; ?>  
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?> 

<?php if ($drm->exist('releve_non_apurement') && count($drm->releve_non_apurement)): ?>
<div class="col-xs-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-center">Relevé de non apurement</h3>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>                        
                    <th>Numéro de document</th>
                    <th>Date d'expédition</th>
                    <th>Numéro d'accises</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($drm->releve_non_apurement as $num_non_apurement => $non_apurement): ?>
                <tr> 
                    <td><?php echo $non_apurement->numero_document; ?></td>                       
                    <td><?php echo $non_apurement->date_emission; ?></td>
                    <td><?php echo $non_apurement->numero_accise; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?> 
<?php if ($drm->quantite_sucre || $drm->observations): ?>
<div class="col-xs-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-center">Compléments d'information</h3>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <?php if ($drm->quantite_sucre): ?>
                <tr>
                    <th>Quantité de sucre</th>
                </tr>
                <tr> 
                    <td class="drm_quantite_sucre_volume">
                        <?php echo $drm->quantite_sucre ?> quintals
                    </td>
                </tr>
            <?php endif; ?> 
            <?php if ($drm->observations): ?>
                <tr>
                    <th>Observations</th>
                </tr>    
                <tr>
                    <td>
                    <?php echo $drm->observations; ?>
                    </td>
                </tr>

            <?php endif; ?> 
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
</div>    