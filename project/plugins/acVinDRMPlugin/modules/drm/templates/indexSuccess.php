<ol class="breadcrumb">
    <li><a href="<?php echo url_for('drm') ?>" class="active">DRM</a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
        <?php include_component('drm', 'formEtablissementChoice') ?>
    </div>
</div>
<?php $drm_controles = DRMClient::getDRMControles(); if(!empty($drm_controles)) : ?>
<div class="row">
    <h2 style="margin-left: 1em;" >Liste des DRM avec des points des contrôles</h2>
    <table class="table table-bordered table-condensed table-striped">
		<thead>
        	<tr>
                <th class="col-xs-1">Période</th>
                <th class="col-xs-2">DRM type</th>
                <th class="col-xs-5">Établissement</th>
                <th class="col-xs-2">Status</th>
                <th class="col-xs-1">Nombre de controles</th>
                <th class="col-xs-1">Visualisation</th>
            </tr>
		</thead>
		<tbody>
		<?php foreach ($drm_controles as $identifiant => $drm_controle): ?>
                <tr>
                	<td class="text-center" ><a href="<?php echo url_for('drm_visualisation', array('identifiant' => $identifiant, 'periode_version' => $drm_controle->doc['periode'])) ?>"><?php echo $drm_controle->doc["periode"]; ?></a></td>
                    <td class="text-center"><?php echo $drm_controle->doc["type_creation"]; ?></td>
                    <td class="text-left"><?php echo $drm_controle->doc["societe"]["raison_sociale"]; ?></td>
                    <td class="text-center"><?php echo $drm_controle->doc["etape"]; ?></td>
                    <td class="text-center"><?php echo DRMClient::getNbControlesDRM($drm_controle->doc["controles"]);?></td>
                    <td class="text-center"><a class="btn btn-sm btn-default" href="<?php echo url_for('drm_visualisation', array('identifiant' => $identifiant, 'periode_version' => $drm_controle->doc['periode'])) ?>">Visualiser</a></td>
                </tr>
        <?php endforeach; ?>
        </tbody>
	</table>
</div>
<?php endif; ?>