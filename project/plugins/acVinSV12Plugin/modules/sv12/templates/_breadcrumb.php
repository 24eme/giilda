<?php use_helper('Orthographe'); ?>

<ol class="breadcrumb">
    <li><a href="<?php echo url_for('sv12') ?>">SV12</a></li>
    <li><a href="<?php echo url_for('sv12_etablissement', array('identifiant' => $sv12->getEtablissementObject()->identifiant)) ?>"><?php echo $sv12->getEtablissementObject()->nom ?> (<?php echo $sv12->getEtablissementObject()->identifiant ?>)</a></li>
    <li><a class="active" href="">SV12 de <?php echo $sv12->campagne ?></a></li>
</ol>
