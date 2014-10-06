<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="contenu">
	<form action="<?php echo url_for('societe_upload'); ?>" method="POST" enctype="multipart/form-data" >
		<?php
		echo $formUploadCSVNoCVO->renderHiddenFields();
		echo $formUploadCSVNoCVO->renderGlobalErrors();
		?>
	
		<ul>
                    <li class="ligne_form">                        
				<?php echo $formUploadCSVNoCVO['file']->render() ?>
                    </li>
                    <li class="ligne_form"> 
				<?php echo $formUploadCSVNoCVO['file']->renderError(); ?>
                    </li>
		</ul>
		
		<div class="btn_form">
			<button type="submit" id="alerte_valid" class="btn_majeur btn_valider">Ajouter</button>
		</div>
	</form>
</div>