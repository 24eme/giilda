<?php if (isset($id) && !isset($soussigne)): ?>
<?php $soussigne = EtablissementClient::getInstance()->find($id) ?>
<?php endif; ?>
<?php if (!$soussigne) return ; ?>
<strong><?php echo $soussigne->nom ?></strong><br />
<?php echo $soussigne->siege->adresse ?><br />
<?php echo $soussigne->siege->code_postal ?><?php echo $soussigne->siege->commune ?><br />
<?php if($soussigne->email || $soussigne->telephone ): ?>
<?php echo $soussigne->email ?> <?php echo $soussigne->telephone ?><br />
<?php endif; ?>
<?php if($soussigne->cvi): ?>
<span class="text-muted"><?php echo $soussigne->cvi ?></span><br />
<?php endif; ?>
<?php if($soussigne->cvi): ?>
<span class="text-muted"><?php echo $soussigne->no_accises ?></span><br />
<?php endif; ?>