<ul class="list-unstyled">
    <?php foreach ($points as $controle): ?>
        <li>
            <?php if ($controle->getRawValue()->getLien()) : ?>
                <?php echo $controle->getRawValue()->getMessage() ?> : <a href="<?php echo $controle->getRawValue()->getLien() ?>">                    
                    <?php echo $controle->getRawValue()->getInfo() ?></a>
            <?php else: ?>
                <?php if ($controle->getRawValue()->getMessage()): ?>
                    <?php echo $controle->getRawValue() ?>
                <?php else: ?>
                    <?php echo $controle->getRawValue()->getInfo(); ?>
                <?php endif; ?> 
        <?php endif; ?>
        </li>
<?php endforeach; ?>
</ul>