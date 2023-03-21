<?php if ($route == 'common_accueil'): ?>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
    		<?php if ($route_etablissement && $etablissement && !$actif): ?>
            <a tabindex="-1" class="navbar-brand <?php echo $actif ? "active" : null ?>" href="<?php echo url_for($route_etablissement, array('identifiant' => $etablissement->identifiant)); ?>"><?php echo $libelle; ?></a>
    		<?php else: ?>
            <a tabindex="-1" class="navbar-brand <?php echo $actif ? "active" : null ?>" href="<?php echo url_for($route) ?>"><?php echo $libelle; ?></a>
    		<?php endif; ?>
        </div>
<?php else: ?>
  <?php if(isset($teledeclaration) && $teledeclaration): ?>
      <li class="<?php echo $actif ? "active" : null ?>">
            <a tabindex="-1" href="<?php echo url_for($route, array('identifiant' => $identifiant)); ?>" target="<?php echo $target; ?>">
                <?php echo $libelle ?>
            </a>
        </li>
  <?php else: ?>
    <?php if ($route_etablissement && $etablissement && !$actif): ?>
        <li class="<?php echo $actif ? "active" : null ?>">
            <a tabindex="-1" href="<?php echo url_for($route_etablissement, $etablissement); ?>" target="<?php echo $target; ?>">
                <?php echo $libelle ?>
            </a>
        </li>
    <?php else: ?>
        <li class="<?php echo $actif ? "active" : null ?>">
            <a tabindex="-1" href="<?php echo url_for($route); ?>" target="<?php echo $target; ?>">
                <?php echo $libelle ?>
            </a>
        </li>
    <?php endif; ?>
<?php endif; ?>
<?php endif; ?>
