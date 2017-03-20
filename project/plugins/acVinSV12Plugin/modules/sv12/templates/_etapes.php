<nav class="navbar navbar-default ">
    <ul class="nav navbar-nav">
        <li class="<?php if($etape == 'saisie'): ?>active<?php endif; ?>">
           <a href="<?php echo url_for('sv12_update', $sv12) ?>">
                <span>Saisie des volumes</span>
                <small class="hidden">Etape 1</small>
            </a>
        </li>
        <li class="<?php if($etape == 'validation'): ?>active<?php endif; ?>">
           <a href="<?php echo url_for('sv12_validation', $sv12) ?>">
                <span>Validation</span>
                <small class="hidden">Etape 2</small>
            </a>
        </li>
    </ul>
</nav>
