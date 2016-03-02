<div class="<?php if (isset($smallBlock)): ?>col-xs-12 <?php else: ?>col-xs-6 <?php endif; ?> <?php if (isset($smallBlock)): ?>text-center<?php endif; ?>">
    <address class="<?php if (!isset($smallBlock)): ?>lead<?php endif ?>">
        <?php echo $compte->adresse; ?><br />
        <?php if ($compte->adresse_complementaire) : ?><?php echo $compte->adresse_complementaire ?><br /><?php endif ?>
        <?php echo $compte->code_postal; ?> <?php echo $compte->commune; ?> <small class="text-muted">(<?php echo $compte->pays; ?>)</small>
    </address>
</div>