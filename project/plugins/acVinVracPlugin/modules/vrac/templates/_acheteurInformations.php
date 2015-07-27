<?php if (!$acheteur) return ; ?>
<strong><?php echo $acheteur->nom ?></strong><br />
<?php echo $acheteur->siege->adresse ?><br />
<?php echo $acheteur->siege->code_postal ?><?php echo $acheteur->siege->commune ?><br />
<?php if($acheteur->email || $acheteur->telephone ): ?>
<?php echo $acheteur->email ?> <?php echo $acheteur->telephone ?><br />
<?php endif; ?>
<?php if($acheteur->cvi): ?>
<span class="text-muted"><?php echo $acheteur->cvi ?></span><br />
<?php endif; ?>
<?php if($acheteur->cvi): ?>
<span class="text-muted"><?php echo $acheteur->no_accises ?></span><br />
<?php endif; ?>