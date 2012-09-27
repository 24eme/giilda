<?php if ($route_etablissement && $etablissement && !$actif): ?>
    <li class="<?php echo $actif ? "actif" : null ?>">
        <a href="<?php echo url_for($route_etablissement, $etablissement); ?>">
            <?php echo $libelle ?>
        </a>
    </li>
<?php else: ?>
    <li class="<?php echo $actif ? "actif" : null ?>">
        <a href="<?php echo url_for($route); ?>">
            <?php echo $libelle ?>
        </a>
    </li>
<?php endif; ?>