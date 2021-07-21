<ol class="breadcrumb">
    <li><a href="<?php echo url_for('ds') ?>">Déclaration de Stock</a></li>
    <li><a href="<?php echo url_for('ds_etablissement', array('identifiant' => $etablissement->identifiant)) ?>"><?php echo $etablissement->nom ?> (<?php echo $etablissement->identifiant ?>)</a></li>
</ol>
