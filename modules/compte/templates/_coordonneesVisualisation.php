<fieldset>
	<div class="form_ligne">
		<legend>Adresse</legend>
	</div>
	<div class="form_ligne">
		<label for="adresse">
		Adresse :
                </label>
                <?php echo $compte->adresse; ?>
	</div>
	<div class="form_ligne">
		<label for="adresse_complementaire">
			Adresse complémentaire :
		</label>
		<?php echo $compte->adresse_complementaire; ?>
	</div>
	<div class="form_ligne">
		<label for="code_postal">
			Code postal :
		</label>
		<?php echo $compte->code_postal; ?>
	</div>
	<div class="form_ligne">
		<label for="commune">
			Commune :
		</label>
		<?php echo $compte->commune; ?>
	</div>                
	<div class="form_ligne">
		<label for="cedex">
			Cedex :
		</label>
		<?php echo $compte->cedex; ?>
	</div>                 
	<div class="form_ligne">
		<label for="pays">
			Pays :
		</label>
		<?php echo $compte->pays; ?>
	</div>
</fieldset>
<fieldset>
	<div class="form_ligne">
		<legend>E-mail / téléphone / fax</legend>
	</div>
	<div class="form_ligne">
		<label for="email">
			E-mail : 
		</label>
		<?php echo $compte->email; ?>
	</div>
    	<div class="form_ligne">
		<label for="telephone_perso">
			Téléphone perso :
		</label>
		<?php echo $compte->telephone_perso; ?>
	</div>
	<div class="form_ligne">
		<label for="telephone_bureau">
			Téléphone bureau :
		</label>
		<?php echo $compte->telephone_bureau; ?>
	</div>
	<div class="form_ligne">
		<label for="telephone_mobile">
			Téléphone mobile :
		</label>
		<?php echo $compte->telephone_mobile; ?>
	</div>
	<div class="form_ligne">
		<label for="fax">
			Fax :
		</label>
		<?php echo $compte->fax; ?>
	</div>
</fieldset>
<fieldset>
	<div class="form_ligne">
		<legend>Tags - étiquettes</legend>
	</div>
	<div class="form_ligne">
		<label for="tags" class="label_liste">Tags</label>
		<ul class="tags">
                </ul>
	</div>
</fieldset>