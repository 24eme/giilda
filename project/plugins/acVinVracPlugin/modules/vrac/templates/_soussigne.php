<?php if (isset($id) && !isset($soussigne)): ?>
<?php $soussigne = EtablissementClient::getInstance()->find($id) ?>
<?php endif; ?>
<?php if (!$soussigne) return ; ?>
<strong><?php echo $soussigne->nom ?></strong><br />
<?php echo $soussigne->siege->adresse ?> <?php echo $soussigne->siege->code_postal ?> <?php echo $soussigne->siege->commune ?><br />
<?php if($soussigne->email || $soussigne->telephone ): ?>
<?php echo $soussigne->email ?> <?php echo $soussigne->telephone ?><br />
<?php endif; ?>
<?php if($soussigne->cvi): ?>
<span class="text-muted">CVI : <strong><?php echo $soussigne->cvi ?></strong></span>
<?php endif; ?>
<?php if($soussigne->cvi): ?>
<span class="text-muted">N° Accises : <strong><?php echo $soussigne->no_accises ?></strong></span>
<?php endif; ?>
<?php if($soussigne->carte_pro): ?>
<br /><span class="text-muted">N° Carte professionnel : <strong><?php echo $soussigne->carte_pro ?></strong></span>
<?php endif; ?>