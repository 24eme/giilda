<div id="consultation_pagination">
	<?php if ($page > 1): ?>
	<a class="pagination_link" href="<?php echo url_for('alerte', array('p' => ($page - 1))) ?>&<?php echo esc_raw($consultationFilter) ?>">&lt;&lt;</a>
	<?php endif; ?>
	(<strong><?php echo $page ?></strong>/<?php echo $nbPage ?>)
	<?php if ($page < $nbPage): ?>
	<a class="pagination_link" href="<?php echo url_for('alerte', array('p' => ($page + 1))) ?>&<?php echo esc_raw($consultationFilter) ?>">&gt;&gt;</a>
	<?php endif; ?>
</div>