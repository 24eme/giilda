<?php if (!$vendeur) return ; ?>
<strong><?php echo $vendeur->nom ?></strong><br />
<?php echo $vendeur->siege->adresse ?><br />
<?php echo $vendeur->siege->code_postal ?><?php echo $vendeur->siege->commune ?><br />
<?php echo $vendeur->email ?> <?php echo $vendeur->telephone ?><br />
<span class="text-muted"><?php echo $vendeur->cvi ?></span><br />
<span class="text-muted"><?php echo $vendeur->no_accises ?></span><br />