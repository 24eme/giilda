
<?php
	echo $compteForm->renderHiddenFields();
	echo $compteForm->renderGlobalErrors();
?>

<fieldset>
	<div class="form_ligne">
		<legend>Adresse</legend>
	</div>
	<div class="form_ligne">
		<?php echo $compteForm['adresse']->renderError(); ?>
		<label for="adresse">
			<?php echo $compteForm['adresse']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['adresse']->render(array('class' => 'champ_long')); ?>
	</div>
	<div class="form_ligne">
		<label for="adresse_complementaire">
			<?php echo $compteForm['adresse_complementaire']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['adresse_complementaire']->render(array('class' => 'champ_long')); ?>
		<?php echo $compteForm['adresse_complementaire']->renderError(); ?>
	</div>
	<div class="form_ligne">
		<label for="code_postal">
			<?php echo $compteForm['code_postal']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['code_postal']->render(); ?>
		<?php echo $compteForm['code_postal']->renderError(); ?>
	</div>
	<div class="form_ligne">
		<label for="commune">
			<?php echo $compteForm['commune']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['commune']->render(array('class' => 'champ_long')); ?>
		<?php echo $compteForm['commune']->renderError(); ?>
	</div>                
	<div class="form_ligne">
		<label for="cedex">
			<?php echo $compteForm['cedex']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['cedex']->render(); ?>
		<?php echo $compteForm['cedex']->renderError(); ?>
	</div>                 
	<div class="form_ligne">
		<label for="pays">
			<?php echo $compteForm['pays']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['pays']->render(); ?>
		<?php echo $compteForm['pays']->renderError(); ?>
	</div>
</fieldset>
<fieldset>
	<div class="form_ligne">
		<legend>E-mail / téléphone / fax</legend>
	</div>
	<div class="form_ligne">
		<label for="email">
			<?php echo $compteForm['email']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['email']->render(); ?>
		<?php echo $compteForm['email']->renderError(); ?>
	</div>
	<div class="form_ligne">
		<label for="telephone_bureau">
			<?php echo $compteForm['telephone_bureau']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['telephone_bureau']->render(); ?>
		<?php echo $compteForm['telephone_bureau']->renderError(); ?>
	</div>
	<div class="form_ligne">
		<label for="telephone_mobile">
			<?php echo $compteForm['telephone_mobile']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['telephone_mobile']->render(); ?>
		<?php echo $compteForm['telephone_mobile']->renderError(); ?>
	</div>
	<div class="form_ligne">
		<label for="fax">
			<?php echo $compteForm['fax']->renderLabel(); ?>
		</label>
		<?php echo $compteForm['fax']->render(); ?>
		<?php echo $compteForm['fax']->renderError(); ?>
	</div>
</fieldset>
<fieldset>
	<div class="form_ligne">
		<legend>Tags - étiquettes</legend>
	</div>
</fieldset>

<!--        <div class="section_label_maj" id="tags">
<?php //echo $compteForm['tags']->renderLabel(); ?>
<?php //echo $compteForm['tags']->render(); ?>
<?php //echo $compteForm['tags']->renderError(); ?>
	</div>-->
