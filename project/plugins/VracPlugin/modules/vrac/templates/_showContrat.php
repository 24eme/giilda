<?php

?>

    <ul>
        <li>
            <h2>
            Les soussignés
            </h2>
            <div class="btnModification">
                <a href="<?php echo url_for('vrac_soussigne',$vrac); ?>">Modifier</a>
            </div>
            <section id="soussigne_recapitulatif">
            <?php
            include_partial('soussigneRecapitulatif', array('vrac' => $vrac));
            ?>
            </section>            
        </li>
        <li>
            <h2>
            Le marché
            </h2>
            <div class="btnModification">
                <a href="<?php echo url_for('vrac_marche',$vrac); ?>">Modifier</a>
            </div>
            <section id="marche_recapitulatif">
            <?php
            include_partial('marcheRecapitulatif', array('vrac' => $vrac));
            ?>
            </section> 
        </li>
        <li>
            <h2>
            Les conditions
            </h2>
            <div class="btnModification">
                <a href="<?php echo url_for('vrac_condition',$vrac); ?>">Modifier</a>
            </div>
            <section id="conditions_recapitulatif">
            <?php
            include_partial('conditionsRecapitulatif', array('vrac' => $vrac));
            ?>
            </section> 
        </li>
    </ul>