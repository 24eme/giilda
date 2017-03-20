<div class="row row-centered" style="text-align:center">
		<span class="text-right">

			<a 	<?php if ($page <= 1): ?>	disabled="disabled" <?php endif; ?> class="btn btn-default page_precedente" href="<?php echo url_for('alerte', array('p' => ($page - 1))) ?>&<?php echo esc_raw($consultationFilter) ?>"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;</a>

		</span>
		<span class="text-center">
				<span>&nbsp;<strong><?php echo $page ?></strong>/<?php echo $nbPage ?>&nbsp;</span>
		</span>
		<span class="text-left">

			<a 	<?php if ($page >= $nbPage): ?> disabled="disabled" <?php endif; ?> class="btn btn-default page_suivante" href="<?php echo url_for('alerte', array('p' => ($page + 1))) ?>&<?php echo esc_raw($consultationFilter) ?>">&nbsp;<span class="glyphicon glyphicon-arrow-right"></span></a>
		</span>
</div>
<br/>
