<?php
    if($vrac->etape==null) $vrac->etape=0;
    $pourcentage = ($vrac->etape) * 25;
?>
    <ol id="rail_etapes">
            <?php if($vrac->etape == 0) echo "<a href=".url_for('vrac_nouveau').">"; ?>
            <?php if($vrac->etape > 0) echo "<a href=".url_for('vrac_soussigne',$vrac).">"; ?>
        <li class="<?php echo ($actif==1)? 'actif' : '' ?>">            
                <span>1. <span>Soussignés</span></span>            
        </li>
            <?php echo "</a>"; ?>
        
            <?php if($vrac->etape >= 1) echo "<a href=".url_for('vrac_marche',$vrac).">"; ?>
        <li class="<?php echo ($actif==2)? 'actif' : '' ?>">
                <span>2. <span>Marché</span></span>            
        </li>
            <?php if($vrac->etape >= 1) echo "</a>"; ?> 

            <?php if($vrac->etape > 2) echo "<a href=".url_for('vrac_condition',$vrac).">"; ?>
        <li class="<?php echo ($actif==3)? 'actif' : '' ?>">            
                <span>3. <span>Conditions</span></span>
        </li>
            <?php if($vrac->etape > 2) echo "</a>"; ?> 
        
            <?php if($vrac->etape > 3) echo "<a href=".url_for('vrac_validation',$vrac).">"; ?>
        <li class="<?php echo ($actif==4)? 'actif' : '' ?>">       
            <span>4. <span>Validation</span></span>
        </li>
            <?php if($vrac->etape > 3) echo "</a>"; ?> 
        
        
    </ol>