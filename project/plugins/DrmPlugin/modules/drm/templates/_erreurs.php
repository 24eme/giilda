<div class="error_list">
    <h3>Points bloquants</h3>
    <ol>
        <?php foreach ($drmValidation->getErrors() as $identifiant => $error): ?>
            <li>
            	<a href="<?php echo url_for('drm_show_error', array('sf_subject' => $drm,
				            										'type' => $error->getType(),
				            										'identifiant' => $identifiant)) ?>">
					<?php echo $error->getMessage(); ?>
            	</a>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
<br />