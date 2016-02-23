<ol class="breadcrumb">
    <li><a href="<?php echo url_for('statistiques') ?>">Statistique</a></li>
    <li><a href="<?php echo url_for('statistiques_drm') ?>" class="active">DRM</a></li>
</ol>

<div class="row" id="statistiques">
    <div class="col-xs-12">
			
    		<h2><?php echo $statistiquesConfig['title'] ?></h2>
    		
    		<?php include_partial('formFilter', array('url' => url_for('statistiques_drm'), 'collapseIn' => $collapseIn, 'form' => $form, 'urlCsv' => url_for('statistiques_drm_csv'))) ?>
    		<hr />
    		<p><strong><?php echo number_format($nbHits, 0, ',', ' ') ?></strong> résultat<?php if ($nbHits > 1): ?>s<?php endif; ?> </p>
    		
    		<?php if ($nbHits > 0): ?>
    			<?php include_partial('resultDrmStatistiqueFilter', array('hits' => $hits)) ?>
    		<?php else: ?>
    			<p>Aucun résultat pour la recherche</p>
    		<?php endif; ?>
    		
    		<?php if ($nbPage > 1): ?>
    			<?php include_partial('paginationStatistiqueFilter', array('type' => 'drm', 'nbPage' => $nbPage, 'page' => $page, 'filters' => $filters)) ?>
    		<?php endif; ?>
    		
    </div>
</div>
