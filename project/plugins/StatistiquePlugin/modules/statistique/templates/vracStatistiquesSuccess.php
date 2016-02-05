<ol class="breadcrumb">
    <li><a href="<?php echo url_for('statistiques') ?>" class="active">Page d'accueil</a></li>
</ol>

<div class="row">
    <div class="col-xs-12">
			
    		<h2><strong><?php echo $statistiquesConfig['title'] ?></strong></h2>
    		
    		<?php include_partial('formFilter', array('url' => url_for('statistiques_vrac'), 'fields' => $fields, 'form' => $form)) ?>
    		<hr />
    		<p><strong><?php echo number_format($nbHits, 0, ',', ' ') ?></strong> résultat<?php if ($nbHits > 1): ?>s<?php endif; ?></p>
    		
    		<?php if ($nbHits > 0): ?>
    			<?php include_partial('resultVracStatistiqueFilter', array('hits' => $hits)) ?>
    		<?php else: ?>
    			<p>Aucun résultat pour la recherche</p>
    		<?php endif; ?>
    		
    		<?php if ($nbPage > 1): ?>
    			<?php include_partial('paginationStatistiqueFilter', array('type' => 'vrac', 'nbPage' => $nbPage, 'page' => $page)) ?>
    		<?php endif; ?>
    		
    </div>
</div>