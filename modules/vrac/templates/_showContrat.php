<?php $isValidation = (is_null($vrac->valide->statut) || ($vrac->valide->statut == "NONSOLDE")); ?>
<ul>
        <li>
            <div class="style_label">1. Les soussignés</div>
            <div id="soussigne_recapitulatif">
            <?php
            include_partial('soussigneRecapitulatif', array('vrac' => $vrac));
            ?>
            </div>  
            <?php
            if($isValidation)
                {
            ?>
            <div class="btnModification f_right">
                <a href="<?php echo url_for('vrac_soussigne',$vrac); ?>" class="btn_majeur btn_modifier">Modifier</a>
            </div> 
            <?php 
                }
            ?>
        </li>
        <li>
            <div class="style_label">2. Le marché</div>           
            <section id="marche_recapitulatif">
            <?php
            include_partial('marcheRecapitulatif', array('vrac' => $vrac));
            ?>
            </section>
            <?php
            if($isValidation)
                {
            ?>
            <div class="btnModification f_right">
                <a href="<?php echo url_for('vrac_marche',$vrac); ?>" class="btn_majeur btn_modifier">Modifier</a>
            </div>
            <?php 
                }
            ?>
        </li>
        <li>
            <div class="style_label">3. Les conditions</div>            
            <section id="conditions_recapitulatif">
            <?php
            include_partial('conditionsRecapitulatif', array('vrac' => $vrac));
            ?>
            </section>
            <?php
            if($isValidation)
                {
            ?>
            <div class="btnModification f_right">
                <a href="<?php echo url_for('vrac_condition',$vrac); ?>" class="btn_majeur btn_modifier">Modifier</a>
            </div>
            <?php 
                }
            ?>
        </li>
    </ul>