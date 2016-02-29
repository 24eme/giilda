	<div class="text-center">
        <nav>	
    		<ul class="pagination">
                <?php if ($page > 1) : ?>
    				<li><a href="<?php echo (!$filters)? url_for('statistiques_'.$type, array('p' => 1)) : url_for('statistiques_'.$type, array_merge(array('p' => 1), $filters->getRawValue())); ?>"><span aria-hidden="true"><<</span></a></li>
    				<li><a href="<?php echo (!$filters)? url_for('statistiques_'.$type, array('p' => ($page - 1))) : url_for('statistiques_'.$type, array_merge(array('p' => ($page - 1)), $filters->getRawValue())); ?>"><span aria-hidden="true"><</span></a></li>
                <?php endif; ?>
                <li><a href="">page <?php echo $page; ?> sur <?php echo $nbPage; ?></a></li>
    			<?php if ($page < $nbPage): ?>
                	<li><a href="<?php echo (!$filters)? url_for('statistiques_'.$type, array('p' => ($page + 1))) : url_for('statistiques_'.$type, array_merge(array('p' => ($page + 1)), $filters->getRawValue())); ?>"> > </a></li>
                    <li><a href="<?php echo (!$filters)? url_for('statistiques_'.$type, array('p' => $nbPage)) : url_for('statistiques_'.$type, array_merge(array('p' => $nbPage), $filters->getRawValue())); ?>" class="btn_majeur page_suivante"> >> </a></li>
                <?php endif; ?>
    		</ul>
        </nav>
    </div>