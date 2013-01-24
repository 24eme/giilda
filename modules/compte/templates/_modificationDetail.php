<div class="form_contenu">
		<?php
		echo $compteForm->renderHiddenFields();
		echo $compteForm->renderGlobalErrors();
		?>
                <div class="form_ligne">
			<?php echo $compteForm['statut']->renderError(); ?>
			<label for="civilite">
				<?php echo $compteForm['statut']->renderLabel('Statut *',array('class')); ?>
			</label>
			<?php echo $compteForm['statut']->render(); ?>
		</div>
    
		<div class="form_ligne">
			<?php echo $compteForm['civilite']->renderError(); ?>
			<label for="civilite">
				<?php echo $compteForm['civilite']->renderLabel(); ?>
			</label>
			<?php echo $compteForm['civilite']->render(); ?>
		</div>
		<div class="form_ligne">
			<label for="prenom">
				<?php echo $compteForm['prenom']->renderLabel(); ?>
			</label>
			<?php echo $compteForm['prenom']->render(); ?>
			<?php echo $compteForm['prenom']->renderError(); ?>
		</div>
		<div class="form_ligne">
			<label for="nom">
				<?php echo $compteForm['nom']->renderLabel(); ?>
			</label>
			<?php echo $compteForm['nom']->render(); ?>
			<?php echo $compteForm['nom']->renderError(); ?>
		</div>
		<div class="form_ligne">
			<label for="fonction">
				<?php echo $compteForm['fonction']->renderLabel(); ?>
			</label>
			<?php echo $compteForm['fonction']->render(); ?>
			<?php echo $compteForm['fonction']->renderError(); ?>
		</div>                
		<div class="form_ligne">
			<label for="commentaire">
				<?php echo $compteForm['commentaire']->renderLabel(); ?>
			</label>
			<?php echo $compteForm['commentaire']->render(); ?>
			<?php echo $compteForm['commentaire']->renderError(); ?>
		</div> 
</div>
