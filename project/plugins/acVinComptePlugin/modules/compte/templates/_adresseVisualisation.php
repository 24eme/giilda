<div class="text-center">
    <address class="<?php if (!isset($smallBlock) || !$smallBlock): ?>lead<?php endif ?>">
        <?php echo $compte->adresse; ?><br />
        <?php if ($compte->adresse_complementaire) : ?><?php echo $compte->adresse_complementaire ?><br /><?php endif ?>
        <?php echo $compte->code_postal; ?> <?php echo $compte->commune; ?> <small class="text-muted">(<?php echo $compte->pays; ?>)</small>
    </address>
</div>