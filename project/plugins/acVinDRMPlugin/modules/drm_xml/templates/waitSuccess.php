<?php
use_helper('Asset');
$interpro = strtolower(sfConfig::get('app_teledeclaration_interpro'));
?>
<div style="text-align: center; top: 30%; position: absolute; width: 99%;"><center>
<img src="<?php echo _compute_public_path('/images/logo_'.$interpro, null, 'png') ?>" height="150" style="display: inline-block"/>
<img src="<?php echo _compute_public_path('/images/2douane', null, 'gif') ?>" width="530" height="150" style="display: inline-block"/>
<p>Transmission des données à douane.gouv.fr en cours... <br/>Veuillez patienter</p>
</center></div>
<form id="form_transmission" method="post" action="<?php echo url_for('drm_ciel', $drm); ?>">
</form>
<script src="<?php echo _compute_public_path('/js/lib/jquery-1.7.2.min.js', null, 'js') ?>"></script>
<script type="text/javascript">
        $(document).ready(function() {
           setTimeout("$('#form_transmission').submit();", 1000);
        });
</script>
