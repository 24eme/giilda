<?php
use_helper('Vrac');
if(isset($vrac)) $campagneDate = dateCampagneViticole($vrac->dateSignature);
if(isset($vracs)) $campagneDates = array('2012/2013','2011/2012','2010/2011');

?>
<div id="campagne_viticole" class="bloc_col">
    <h2>Campagne viticole</h2>

    <div class="contenu">
            <div id="campagne_viticoleChoice">
                <?php if($visualisation): ?>
                    <span><?php echo $campagneDate; ?></span>
                <?php else: ?>
                    <select id="campagne_viticole_date">
                    <?php foreach ($campagneDates as $c):?>
                        <option <?php echo ($c==$campagne)? 'selected="selected"' : ''; ?>><?php echo $c; ?></option>
                    <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
    </div>
</div>