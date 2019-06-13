<?php use_helper("Date"); ?>
<?php use_helper('DRM'); ?>
<!-- #principal -->
<section id="principal" class="drm">
    <div id="application_drm">
        <?php if (!$isTeledeclarationMode): ?>
            <?php include_partial('drm/header', array('drm' => $drm)); ?>
            <ul id="recap_infos_header">
                <li>
                    <label>Nom de l'opérateur : </label><?php echo $drm->getEtablissement()->nom ?><label style="float: right;">Période : <?php echo $drm->periode ?></label>
                </li>
            </ul>
        <?php endif; ?>


		<?php include_partial('drm/etapes', array('drm' => $drm, 'isTeledeclarationMode' => $isTeledeclarationMode, 'etape_courante' => DRMClient::ETAPE_VALIDATION)); ?>

		<div id="contenu_etape">

<fieldset class="validation_drm_tables" id="fieldset_libelles_<?php echo DRMClient::$types_libelles[DRM::DETAILS_KEY_SUSPENDU] ?>">
<form action="<?php echo url_for('drm_edition_libelles', $form->getObject()) ?>" method="post">
	<?php echo $form->renderGlobalErrors(); ?>
    <?php echo $form->renderHiddenFields(); ?>

    <p>Indiquer les libellés correspondant à ceux déclarés sur prodouane.</p>
<br/>
	<?php if($form->hasDetailsKey(DRM::DETAILS_KEY_SUSPENDU)): ?>
		<div id="drm_visualisation_stock_SUSPENDU" class="section_label_maj">
			<h2>Mouvements <?php echo DRMClient::$types_libelles[DRM::DETAILS_KEY_SUSPENDU] ?>s</h2>
	<table class="table_recap">
    	<thead>
        	<tr>
				<th class="col-xs-5">Libellé produit</th>
				<th class="col-xs-5">Libellé prodouane</th>
                <th class="col-xs-2">Code prodouane</th>
			</tr>
			</thead>
			<tbody>
				<?php
					foreach ($drm->getProduitsDetails(true, DRM::DETAILS_KEY_SUSPENDU) as $hash => $detail):
					if (isset($form[$hash])):
				?>
				<tr>
					<td><?php echo $form[$hash]->renderLabel() ?></td>
					<?php if ($drm->get($hash)->hasLibelleModified()): ?>
					<td class="has-warning"><?php echo $form[$hash]->render(array('class' => 'form-control')) ?><?php echo $form[$hash]->renderError() ?></td>
					<?php else: ?>
					<td><?php echo $form[$hash]->render(array('class' => 'form-control', 'placeholder' => strip_tags(trim($form[$hash]->renderLabel())))) ?><?php echo $form[$hash]->renderError() ?></td>
					<?php endif; ?>
                    <td><?php echo $form[$hash.'_code']->render(array('class' => 'form-control', 'placeholder' => strip_tags(trim($form[$hash.'_code']->renderLabel())))) ?><?php echo $form[$hash.'_code']->renderError() ?></td>
				</tr>
				<?php endif; endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
	</div>
</fieldset>
<br/>
<fieldset class="validation_drm_tables" id="fieldset_libelles_<?php echo DRMClient::$types_libelles[DRM::DETAILS_KEY_ACQUITTE] ?>">
	<?php if($form->hasDetailsKey(DRM::DETAILS_KEY_ACQUITTE)): ?>
	<div id="drm_visualisation_stock_ACQUITTE" class="section_label_maj">
		<h2>Mouvements <?php echo DRMClient::$types_libelles[DRM::DETAILS_KEY_ACQUITTE] ?>s</h2>
	<table class="table_recap">
    	<thead>
        	<tr>
				<th class="col-xs-6">Libellé produit</th>
				<th class="col-xs-6">Libellé prodouane</th>
                <th class="col-xs-2">Code prodouane</th>
			</tr>
			</thead>
			<tbody>
				<?php
					foreach ($drm->getProduitsDetails(true, DRM::DETAILS_KEY_ACQUITTE) as $hash => $detail):
					if (isset($form[$hash])):
				?>
				<tr>
					<td><?php echo $form[$hash]->renderLabel() ?></td>
					<?php if ($drm->get($hash)->hasLibelleModified()): ?>
					<td class="has-warning"><?php echo $form[$hash]->render(array('class' => 'form-control')) ?><?php echo $form[$hash]->renderError() ?></td>
					<?php else: ?>
					<td><?php echo $form[$hash]->render(array('class' => 'form-control', 'placeholder' => strip_tags(trim($form[$hash]->renderLabel())))) ?><?php echo $form[$hash]->renderError() ?></td>
					<?php endif; ?>
                    <td><?php echo $form[$hash.'_code']->render(array('class' => 'form-control', 'placeholder' => strip_tags(trim($form[$hash.'_code']->renderLabel())))) ?><?php echo $form[$hash.'_code']->renderError() ?></td>
				</tr>
				<?php endif; endforeach; ?>
		</tbody>
	</table>
    <?php endif; ?>

	</div>
</fieldset>
    <div class="btn_etape">
        	<a tabindex="-1" href="<?php echo url_for('drm_validation', $drm) ?>" class="btn_etape_prec"><span>Etape précédente</span></a>
        	<button type="submit" class="btn_etape_suiv"><span>Valider</span></button>
     </div>

</form>
</section>
