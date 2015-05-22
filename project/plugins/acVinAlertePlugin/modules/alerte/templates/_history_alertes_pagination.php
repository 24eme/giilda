<div class="pagination">
	<?php if ($page > 1): ?>
	<a class="btn_majeur page_precedente" href="<?php echo url_for('alerte', array('p' => ($page - 1))) ?>&<?php echo esc_raw($consultationFilter) ?>">Page précedente</a>
	<?php endif; ?>
	<span>(<strong><?php echo $page ?></strong>/<?php echo $nbPage ?>)</span>
	<?php if ($page < $nbPage): ?>
	<a class="btn_majeur page_suivante" href="<?php echo url_for('alerte', array('p' => ($page + 1))) ?>&<?php echo esc_raw($consultationFilter) ?>">Page suivante</a>
	<?php endif; ?>
</div>