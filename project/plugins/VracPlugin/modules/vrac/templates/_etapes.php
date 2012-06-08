<?php  if($vrac->etape==null) $vrac->etape=0; ?>
<div id="statut_vrac">
	<nav id="vrac_etapes">
                <ol>
                    <li class="<?php echo ($actif==1)? 'actif' : '' ?>">
                        <?php if($vrac->etape == 0) echo "<a href=".url_for('vrac_nouveau').">"; ?>
                        <?php if($vrac->etape > 0) echo "<a href=".url_for('vrac_soussigne',$vrac).">"; ?>
                            <span>1. Soussignés</span>
                        <?php echo "</a>"; ?>
                    </li>
                    <li class="<?php echo ($actif==2)? 'actif' : '' ?>">
                        <?php if($vrac->etape >= 1) echo "<a href=".url_for('vrac_marche',$vrac).">"; ?>
                            <span>2. Marché</span>
                        <?php if($vrac->etape >= 1) echo "</a>"; ?> 
                    </li>
                    
                    <li class="<?php echo ($actif==3)? 'actif' : '' ?>">
                        <?php if($vrac->etape > 2) echo "<a href=".url_for('vrac_condition',$vrac).">"; ?>
                            <span>3. Conditions</span>
                        <?php if($vrac->etape > 2) echo "</a>"; ?> 
                    </li>
                    
                    <li class="<?php echo ($actif==4)? 'actif' : '' ?>">                        
                        <?php if($vrac->etape > 3) echo "<a href=".url_for('vrac_validation',$vrac).">"; ?>
                            <span>4. Validation</span>
                        <?php if($vrac->etape > 3) echo "</a>"; ?> 
                    </li>
		</ol>
	</nav>	
	<div id="etat_avancement">
		<p>Vous avez saisi <strong><?php echo $pourcentage ?><span>%</span></strong></p>
		<div id="barre_avancement">
			<div style="width: <?php echo $pourcentage ?>%"></div>
		</div>
	</div>
</div>