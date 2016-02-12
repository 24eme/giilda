<?php
use_helper('Vrac');
if(isset($vrac)) $campagneDate = dateCampagneViticole($vrac->dateSignature);
if(isset($vracs)) $campagneDates = VracClient::getInstance()->listCampagneByEtablissementId($identifiant);

?>

<h3>Campagne viticole</h3>
<?php if($visualisation): ?>
    <span><?php echo $campagneDate; ?></span>
<?php else: ?>
<form class="form-inline">
        <select class="form-control" name="campagne" id="campagne_viticole_date">
        <?php foreach ($campagneDates as $c => $c_libelle):?>
            <option <?php echo ($c==$campagne)? 'selected="selected"' : ''; ?>><?php echo $c_libelle; ?></option>
        <?php endforeach; ?>
        </select>
<button type="submit" class="btn btn-default">Changer</button>
</form>
<?php endif; ?>
