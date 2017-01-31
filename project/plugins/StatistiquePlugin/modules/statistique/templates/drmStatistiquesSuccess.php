<ol class="breadcrumb">
    <li><a href="<?php echo url_for('statistiques') ?>">Recherche</a></li>
    <li><a href="<?php echo url_for('statistiques_drm') ?>" class="active">DRM</a></li>
</ol>

<div class="row" id="statistiques">
    <div class="col-xs-12">

    		<?php include_partial('formFilter', array('nb_results' => $nbHits, 'url' => url_for('statistiques_drm'), 'collapseIn' => $collapseIn, 'form' => $form, 'urlCsv' => url_for('statistiques_drm_csv'))) ?>
    		<hr />

    		<?php if ($nbPage > 1): ?>
    			<?php include_partial('paginationStatistiqueFilter', array('type' => 'drm', 'nbPage' => $nbPage, 'page' => $page, 'filters' => $filters)) ?>
    		<?php endif; ?>

    		<?php if ($nbHits > 0): ?>
    			<?php include_partial('resultDrmStatistiqueFilter', array('hits' => $hits)) ?>
    		<?php endif; ?>

    		<?php if ($nbPage > 1): ?>
    			<?php include_partial('paginationStatistiqueFilter', array('type' => 'drm', 'nbPage' => $nbPage, 'page' => $page, 'filters' => $filters)) ?>
    		<?php endif; ?>

    </div>
</div>
