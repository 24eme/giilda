<?php if (!$mandataire) return ; ?>
<strong><?php echo $mandataire->nom ?></strong><br />
<?php echo $mandataire->siege->adresse ?><br />
<?php echo $mandataire->siege->code_postal ?><?php echo $mandataire->siege->commune ?><br />
<?php echo $mandataire->email ?> <?php echo $mandataire->telephone ?><br />
<span class="text-muted"><?php echo $mandataire->carte_pro ?></span><br />