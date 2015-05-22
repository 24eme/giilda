<?php if ($route_etablissement && $etablissement && !$actif): ?>
    <li class="<?php echo $actif ? "actif" : null ?>">
        <a href="<?php echo url_for($route_etablissement, $etablissement); ?>" target="<?php echo $target; ?>">
            <?php echo $libelle ?>
        </a>
    </li>
<?php else: ?>
    <li class="<?php echo $actif ? "actif" : null ?>">
        <a href="<?php echo url_for($route); ?>" target="<?php echo $target; ?>">
            <?php echo $libelle ?>
        </a>
    </li>
<?php endif; ?>